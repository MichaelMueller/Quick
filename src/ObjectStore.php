<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class ObjectStore implements Interfaces\ObjectStore
{

  const EXT                  = "json";
  const SCALAR               = 0;
  const SERIALIZED_REFERENCE = 1;
  const SERIALIZED_OBJECT    = 2;
  const KEY_FQCN             = 0;
  const KEY_ID               = 1;
  const KEY_OBJECT_DATA      = 2;
  const KEY_META_DATA        = 3;

  function __construct( $DataDir, Interfaces\FileSystem $FileSystem )
  {
    $this->DataDir    = $DataDir;
    $this->FileSystem = $FileSystem;
  }

  function get( $Fqcn, $Id )
  {
    return isset( $this->Objects[ $Fqcn ][ $Id ] ) ? $this->Objects[ $Fqcn ][ $Id ] : $this->loadFromFile( $this->getDataFile( $Fqcn, $Id ) );
  }

  function commit()
  {
    foreach ( $this->Objects as $Fqcn => $ObjectArray )
    {
      foreach ( $ObjectArray as $Id => $Object )
      {
        if ( $Object->hasChanged() )
        {
          $ObjectData = $Object->getData();
          $MetaData   = [];
          foreach ( $ObjectData as $Key => $Value )
          {
            if ( is_scalar( $Value ) )
              $MetaData[ $Key ] = self::SCALAR;
            elseif ( $Value instanceof Interfaces\PersistableObject )
            {
              $ObjectData[ $Key ] = [ get_class( $Value ), $Value->getId() ];
              $MetaData[ $Key ]     = self::SERIALIZED_REFERENCE;
            }
            else
            {
              $ObjectData[ $Key ] = serialize( $Value );
              $MetaData[ $Key ]     = self::SERIALIZED_OBJECT;
            }
          }
          $Data = [ self::KEY_FQCN => $Fqcn, self::KEY_ID => $Id, self::KEY_OBJECT_DATA => $ObjectData, self::KEY_META_DATA => $MetaData ];
          $File = $this->getDataFile( $Fqcn, $Id );

          $File->writeContents( json_encode( $Data, JSON_PRETTY_PRINT ) );
          $Object->setUnchanged();
        }
      }
    }
  }

  public function count( $Fqcn )
  {
    $Files = $this->getDataFiles( $Fqcn, null );
    return count( $Files );
  }

  public function create( $Fqcn, $Id = null )
  {
    $ActualId = $Id;
    if ( $Id )
    {
      if ( $this->exists( $Fqcn, $Id ) )
        return null;
      $this->FileSystem->createFile( $Id . "." . self::EXT, $this->getDataDirPath( $Fqcn ) );
      $ActualId = $Id;
    }
    else
    {
      $DataFile = $this->FileSystem->createRandomFile( null, self::EXT, $this->getDataDirPath( $Fqcn ) );
      $ActualId = $DataFile->getFileName();
    }
    $NewObject                           = new $Fqcn;
    $NewObject->setId($ActualId);    
    if ( ! isset( $this->Objects[ $Fqcn ] ) )
      $this->Objects[ $Fqcn ]              = [];
    $this->Objects[ $Fqcn ][ $ActualId ] = $NewObject;
    return $NewObject;
  }

  function exists( $Fqcn, $Id )
  {
    return $this->getDataFile( $Fqcn, $Id )->exists();
  }

  public function getFirst( $Fqcn )
  {
    $Files = $this->getDataFiles( $Fqcn, 1 );
    return count( $Files ) > 0 ? $this->loadFromFile( $Files[ 0 ] ) : null;
  }

  protected function loadFromFile( Interfaces\File $File )
  {
    if ( ! $File->exists() )
      return null;

    $Data       = json_decode( $File->readContents(), true );
    $MetaData   = $Data[ self::KEY_META_DATA ];
    $ObjectData = $Data[ self::KEY_OBJECT_DATA ];
    $Fqcn       = $Data[ self::KEY_FQCN ];
    $Id         = $Data[ self::KEY_ID ];

    foreach ( $ObjectData as $Key => $Value )
    {
      $Type = $MetaData[ $Key ];
      if ( $Type != self::SCALAR )
      {
        $Object = unserialize( $Value );
        if ( $Type == self::SERIALIZED_REFERENCE )
        {
          $RefFqcn = $Object[ 0 ];
          $RefId   = $Object[ 1 ];
          $Object  = function() use($RefFqcn, $RefId)
          {
            return $this->get( $RefFqcn, $RefId );
          };
        }
        $ObjectData[ $Key ] = $Object;
      }
    }
    /* @var $Object Interfaces\PersistableObject */
    $Object                                          = new $Fqcn;
    $Object->setId( $Id );
    $Object->setData( $ObjectData );
    $Object->setUnchanged();
    if ( ! isset( $this->Objects[ $Fqcn ] ) )
      $this->Objects[ $Fqcn ]                          = [];
    $this->Objects[ $Fqcn ] [ $File->getFileName() ] = $Object;
    return $Object;
  }

  protected function getDataFiles( $Fqcn, $MaxFiles = null )
  {
    $Dir        = $this->getDataDirPath( $Fqcn );
    $Mode       = Interfaces\FileSystem::ONLY_FILES;
    $Recursive  = false;
    $Extensions = [ self::EXT ];
    return $this->FileSystem->getFiles( $Dir, $Mode, $Recursive, $Extensions, $MaxFiles );
  }

  /**
   * 
   * @param string $Fqcn
   * @param string $Id
   * @return File
   */
  protected function getDataDirPath( $Fqcn )
  {
    return $this->DataDir . "/" . $Fqcn;
  }

  /**
   * 
   * @param string $Fqcn
   * @param string $Id
   * @return File
   */
  protected function getDataFile( $Fqcn, $Id )
  {
    return $this->FileSystem->getFileFactory()->createFileObjectFromPath( $this->getDataDirPath( $Fqcn ) . "/" . $Id . "." . self::EXT );
  }

  /**
   *
   * @var string
   */
  protected $DataDir;

  /**
   *
   * @var Interfaces\FileSystem
   */
  protected $FileSystem;

  /**
   *
   * @var array
   */
  protected $Objects;

}

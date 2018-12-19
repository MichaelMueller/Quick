<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class ObjectStore implements Interfaces\ObjectStore
{

  const EXT = "json";

  function __construct( $DataDir, Interfaces\FileSystem $FileSystem )
  {
    $this->DataDir    = $DataDir;
    $this->FileSystem = $FileSystem;
  }

  function load( $Uuid )
  {
    $ObjectFile = $this->getObjectFile( $Fqcn, $Id );
    if ( $ObjectFile->exists() )
    {
      $Data   = json_decode( $ObjectFile->readContents(), true );
      /* @var $Object Interfaces\PersistableObject */
      $Object = new $Fqcn;
      $Object->setId( $Id );
      $Object->setData( $Data );
      $Object->setUnchanged();
    }
    if ( $Create )
    {
      $Object = new $Fqcn;
      $Object->setId( $Id );
      $Object->setUnchanged();
      $this->Objects[$Id] = $Object;
    }
    return null;
  }

  function commit()
  {
    
  }

  /**
   * 
   * @param string $Fqcn
   * @param string $Id
   * @return File
   */
  function getObjectFile( $Fqcn, $Id )
  {
    $this->FileSystem->getFileFactory()->createFileObjectFromPath( $this->DataDir . "/" . $Fqcn . "/" . $Id . "." . self::EXT );
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
   * @var Interfaces\PersistableObject[]
   */
  protected $Objects;

}

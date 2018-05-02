<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class FileNodeDb extends abstracts\NodeDb
{
  
  const UUID = 0;
  const SERIALIZED_OBJECT = 1;
  
  function __construct( $DataDir, \qck\db\interfaces\ArraySerializer $Serializer )
  {
    $this->DataDir = $DataDir;
    $this->Serializer = $Serializer;
  }

  protected function prepareForSerialization( array $NodeData )
  {
    $DataArray = [];
    foreach ( $NodeData as $key => $value )
    {
      if ( $value instanceof interfaces\UuidProvider )
        $DataArray[ $key ] = array ( self::UUID, $value->getUuid() );
      else if ( is_array( $value ) || is_object( $value ) )
        $DataArray[ $key ] = array ( self::SERIALIZED_OBJECT, serialize( $value ) );
      else
        $DataArray[ $key ] = $value;
    }
    return $DataArray;
  }

  protected function recoverFromSerialization( array $DataArray )
  {
    foreach ( $DataArray as $key => $value )
    {
      if ( is_array( $value ) && $value[ 0 ] == self::UUID )
        $value = new NodeRef( $value[ 1 ], $this );
      else if ( is_array( $value ) && $value[ 0 ] == self::SERIALIZED_OBJECT )
        $value = unserialize( $value );
      $DataArray[ $key ] = $value;
    }
    return $DataArray;
  }

  protected function getDataArray( $File )
  {
    return $this->recoverFromSerialization( $this->getRawArray( $File ) );
  }

  protected function getRawArray( $File )
  {
    return $this->Serializer->fromString( file_get_contents( $File ) );
  }

  protected function getFilePath( $Uuid )
  {
    return $this->DataDir . DIRECTORY_SEPARATOR . $Uuid . "." . $this->Serializer->getFileExtensionWithoutDot();
  }

  protected function getDataFilePath( $Uuid )
  {
    return $this->getFilePath( $Uuid . ".Data" );
  }

  protected function insertNode( interfaces\Node $Node )
  {
    $Uuid = $Node->getUuid();
    $File = $this->getFilePath( $Uuid );
    $DataFile = $this->getDataFilePath( $Uuid );
    $MetaData = [ $Node->getUuid(), get_class( $Node ), $Node->getModifiedTime() ];
    $DataArray = $this->prepareForSerialization( $Node->getData() );

    file_put_contents( $File, $this->Serializer->toString( $MetaData ) );
    file_put_contents( $DataFile, $this->Serializer->toString( $DataArray ) );
  }

  protected function loadNode( $Uuid )
  {
    $File = $this->getFilePath( $Uuid );
    if ( file_exists( $File ) )
      return null;

    $DataFile = $this->getDataFilePath( $Uuid );
    $MetaData = $this->getRawArray( $File );
    $Fqcn = $MetaData[ 1 ];
    $Data = $this->getDataArray( $DataFile );
    return new $Fqcn( $Data, $Uuid );
  }

  protected function updateNode( ChangeLog $ChangeLog )
  {
    $Node = $ChangeLog->getNode();
    $Uuid = $Node->getUuid();
    $DataFile = $this->getDataFilePath( $Uuid );

    $DataArray = $Node->getData();
    $DataOnDisk = $this->getDataArray( $DataFile );
    for ( $i = $ChangeLog->getNextIndex(); $i < $ChangeLog->getSize(); $i++ )
    {
      $key = $ChangeLog->getKey( $i );
      if ( $ChangeLog->isAddedEvent( $i ) || $ChangeLog->isModifiedEvent( $i ) )
        $DataOnDisk[ $key ] = $DataArray[ $key ];
      else if ( $ChangeLog->isDeletedEvent( $i ) )
        unset( $DataOnDisk[ $key ] );
    }

    $DataArray = $this->prepareForSerialization( $DataOnDisk );
    file_put_contents( $DataFile, $this->Serializer->toString( $DataArray ) );
  }

  /**
   *
   * @var string
   */
  protected $DataDir;

  /**
   *
   * @var array
   */
  protected $ChangeLogs = [];

  /**
   *
   * @var interfaces\ArraySerializer
   */
  protected $Serializer;

}

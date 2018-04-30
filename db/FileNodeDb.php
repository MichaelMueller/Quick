<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class FileNodeDb extends abstracts\NodeDb
{

  // INDEXES AND TYPDEFS FOR SERIALIZED ARRAYS
  const FQCN_INDEX = 0;
  const DATA_INDEX = 1;
  const MOD_TIME_INDEX = 2;
  const REF_TYPE = 0;
  const OTHER_OBJECT_TYPE = 1;

  function __construct( $DataDir, \qck\db\interfaces\ArraySerializer $Serializer )
  {
    $this->DataDir = $DataDir;
    $this->Serializer = $Serializer;
  }

  public function commit()
  {
    /* @var $Node interfaces\Node */
    foreach ( $this->Nodes as $Node )
    {
      // only store modified nodes
      if ( $Node->getModifiedTime() <= $this->LastCommitTime )
        continue;

      // try to load node and merge the data
      $File = $this->getFilePath( $Uuid );
      $NodeArray = $this->prepareAsArray( $Node, $this->loadNodeArray( $File ) );

      file_put_contents( $File, $this->Serializer->toString( $NodeArray ) );
    }
    $this->LastCommitTime = time();
  }

  public function getNode( $Uuid )
  {
    // check if node is available
    if ( isset( $this->Nodes[ $Uuid ] ) )
      return $this->Nodes[ $Uuid ];
    else
    {
      $File = $this->getFilePath( $Uuid );
      $NodeArray = $this->loadNodeArray( $File );
      if ( $NodeArray )
      {
        $Node = new $NodeArray[ self::FQCN_INDEX ]( $NodeArray[ self::DATA_INDEX ], $Uuid );
        $this->add( $Node );
        return $Node;
      }
    }
    return null;
  }

  protected function prepareAsArray( interfaces\Node $Node, array $PreviousNodeData = [] )
  {
    $NodeArray = [];
    $NodeArray[ self::FQCN_INDEX ] = get_class( $Node );
    $NodeArray[ self::MOD_TIME_INDEX ] = $Node->getModifiedTime();
    $DataArray = $PreviousNodeData ? $PreviousNodeData : [];
    $DataArray = array_merge( $DataArray, $Node->getData() );

    foreach ( $DataArray as $key => $value )
    {
      if ( $value instanceof interfaces\Node )
        $DataArray[ $key ] = array ( self::REF_TYPE, $value->getUuid() );
      else if ( is_array( $value ) || is_array( $value ) )
        $DataArray[ $key ] = array ( self::OTHER_OBJECT_TYPE, serialize( $value ) );
    }

    $NodeArray[ self::DATA_INDEX ] = $DataArray;
    return $NodeArray;
  }

  protected function loadNodeArray( $File )
  {
    if ( !file_exists( $File ) )
      return null;
    $CompleteData = $this->Serializer->fromString( file_get_contents( $File ) );

    foreach ( $CompleteData[ self::DATA_INDEX ] as $key => $value )
    {
      if ( is_array( $value ) && $value[ 0 ] == self::REF_TYPE )
        $value = new NodeRef( $value[ 1 ], $this );
      else if ( is_array( $value ) && $value[ 0 ] == self::OTHER_OBJECT_TYPE )
        $value = unserialize( $value );
      $CompleteData[ self::DATA_INDEX ][ $key ] = $value;
    }
    return $CompleteData;
  }

  protected function getFilePath( $Uuid )
  {
    return $this->DataDir . DIRECTORY_SEPARATOR . $Uuid . "." . $this->Serializer->getFileExtensionWithoutDot();
  }

  /**
   *
   * @var string
   */
  protected $DataDir;

  /**
   *
   * @var interfaces\ArraySerializer
   */
  protected $Serializer;

  /**
   *
   * @var int
   */
  protected $LastCommitTime = 0;

}

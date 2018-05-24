<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
class Root extends Node implements \qck\GraphStorage\Loader
{

  function __construct( $Uuid, $DataDir )
  {
    parent::__construct( $Uuid );
    $this->DataDir = $DataDir;
    $this->loadInternal( $Uuid, true );
  }

  function save()
  {
    $this->saveRecursive( $this );
  }

  function load( $Uuid )
  {
    return $this->loadInternal( $Uuid, false );
  }

  protected function saveRecursive( Node $Node, array &$VisitedNodeUuids = [] )
  {
    // prevent cycles
    $Uuid = $Node->getUuid();
    if ( in_array( $Uuid, $VisitedNodeUuids ) )
      return;
    $VisitedNodeUuids[] = $Uuid;

    // check if the node was registered before
    if ( !isset( $this->Nodes[ $Uuid ] ) )
    {
      $this->Nodes[ $Uuid ] = $Node;
      $this->NodeSaveTimes[ $Uuid ] = -1;
    }

    // go through the raw data to traverse the graph and collect the data
    // that is saved
    $RawData = $Node->getRawData();
    foreach ( $RawData as $key => $value )
    {
      if ( $value instanceof Node )
      {
        // do the recursion
        $this->saveRecursive( $value, $VisitedNodeUuids );
        // make it a reference for saving it potentially later
        $value = new UuidObject( $value->getUuid() );
        $RawData[ $key ] = $value;
      }
    }
    // save it if needed (all Nodes have been replaced by UUIDs)
    if ( $Node->getModifiedTime() > $this->NodeSaveTimes[ $Uuid ] )
    {
      $File = $this->getFilePath( $Uuid );
      file_put_contents( $File, serialize( $RawData ), LOCK_EX );
      $this->NodeSaveTimes[ $Uuid ] = time();
    }
  }

  protected function getFilePath( $Uuid )
  {
    return $this->DataDir . DIRECTORY_SEPARATOR . $Uuid . ".dat";
  }

  function loadInternal( $Uuid, $SelfLoad = false )
  {
    if ( isset( $this->Nodes[ $Uuid ] ) )
      return $this->Nodes[ $Uuid ];
    $File = $this->getFilePath( $Uuid );
    $RawData = file_exists( $File ) ? file_get_contents( $File ) : null;

    /* @var $Node Node */
    if ( $RawData )
    {
      $Node = $SelfLoad ? $this : new Node( $Uuid, $RawData );
      $Node->setData( $RawData );
      $Node->setLoader( $this );
      $this->Nodes[ $Node->getUuid() ] = $Node;
      $this->NodeSaveTimes[ $Node->getUuid() ] = time();
      return $Node;
    }
    return null;
  }

  protected $Nodes = [];
  protected $NodeSaveTimes = [];

  /**
   *
   * @var string
   */
  protected $DataDir;

}

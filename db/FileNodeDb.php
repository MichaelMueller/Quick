<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class FileNodeDb implements \qck\db\interfaces\NodeDb
{

  const KEY_ADDED = 0;
  const KEY_MODIFIED = 1;
  const KEY_DELETED = 2;

  function __construct( $DataDir, \qck\db\interfaces\NodeSerializer $Serializer )
  {
    $this->DataDir = $DataDir;
    $this->Serializer = $Serializer;
  }

  public function add( interfaces\Node $Node )
  {
    $this->addRecursively( $Node );
  }

  function sync()
  {
    // first merge exisitng nodes
    $this->acquireLock();
    foreach ( $this->Nodes as $Node )
    {
      $File = $this->getFilePath( $Node->getUuid() );
      if ( file_exists( $File ) )
      {
        $PersistetNode = $this->load( $File );
        $DataDiff = array_merge( $Node->getData(), $PersistetNode->getData() );
        // remove null values
        $MergedData = array_filter( $MergedData, function($value)
        {
          return $value !== null;
        } );
        $Node->setData( $MergedData );
      }
      $Data = $this->Serializer->toString( $Node );
      file_put_contents( $File, $Data, LOCK_EX );
    }
    $this->releaseLock();
  }

  public function get( $Uuid )
  {
    if ( isset( $this->Nodes[ $Uuid ] ) )
      return $this->Nodes[ $Uuid ];

    $File = $this->getFilePath( $Uuid );
    if ( !file_exists( $File ) )
      return null;

    $Node = $this->load( $File );
    if ( !$Node )
      return null;

    $this->Nodes[ $Uuid ] = $Node;
    $this->add( $Node );
    return $Node;
  }

  public function unload( interfaces\Node $Node )
  {
    if ( isset( $this->Nodes[ $Node->getUuid() ] ) )
      unset( $this->Nodes[ $Node->getUuid() ] );
  }

  protected function acquireLock()
  {
    if ( $this->Fp )
      $this->releaseLock();
    $LockFile = $this->getLockFile();
    if ( !file_exists( $LockFile ) )
      touch( $LockFile );
    $this->Fp = fopen( $LockFile, "w" );
    flock( $this->Fp, LOCK_EX );
  }

  protected function releaseLock()
  {
    if ( !$this->Fp )
      return;
    flock( $this->Fp, LOCK_UN );
    fclose( $this->Fp );
    $this->Fp = null;
  }

  protected function load( $File )
  {
    return $this->Serializer->fromString( file_get_contents( $File ), $this );
  }

  protected function addRecursively( interfaces\Node $Node, &$VisitedNodeUuids = [] )
  {
    $Uuid = $Node->getUuid();
    if ( in_array( $Uuid, $VisitedNodeUuids ) )
      return;
    $VisitedNodeUuids[] = $Uuid;

    // only add if not exists
    if ( !isset( $this->Nodes[ $Uuid ] ) )
      $this->Nodes[ $Uuid ] = $Node;

    foreach ( $Node->getData() as $val )
      if ( $val instanceof interfaces\Node )
        $this->addRecursively( $val, $VisitedNodeUuids );
  }

  protected function getLockFile()
  {
    return $this->DataDir . DIRECTORY_SEPARATOR . "f8b123ae-4ef7-4d06-85fc-591a0b3d8f65";
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
   * @var interfaces\NodeSerializer
   */
  protected $Serializer;

  /**
   *
   * @var array
   */
  protected $Nodes;

  /**
   *
   * @var resource 
   */
  protected $Fp;

}

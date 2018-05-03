<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class FileNodeDb implements \qck\db\interfaces\NodeDb
{

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
    /* @var $Node interfaces\Node */
    foreach ( $this->Nodes as $Node )
    {
      $Uuid = $Node->getUuid();
      $File = $this->getFilePath( $Uuid );
      /* @var $ChangeLog ChangeLog */
      $ChangeLog = isset( $this->ChangeLogs[ $Uuid ] ) ? $this->ChangeLogs[ $Uuid ] : null;

      if ( $ChangeLog )
      {
        $PersistetNode = $this->load( $File );
        $ChangeLog->applyTo( $PersistetNode );
        $Node->merge( $PersistetNode );
        $ChangeLog->clear();
      }
      else
        $this->ChangeLogs[ $Uuid ] = new ChangeLog( $Node );

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

    $this->add( $Node );
    $this->ChangeLogs[ $Uuid ] = new ChangeLog( $Node );
    return $Node;
  }

  public function unload( interfaces\Node $Node )
  {
    $Uuid = $Node->getUuid();
    if ( isset( $this->Nodes[ $Uuid ] ) )
      unset( $this->Nodes[ $Uuid ] );
    if ( isset( $this->ChangeLogs[ $Uuid ] ) )
      unset( $this->ChangeLogs[ $Uuid ] );
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

    foreach ( $Node->keys() as $key )
      if ( $Node->get( $key, false ) instanceof interfaces\Node )
        $this->addRecursively( $Node->get( $key, false ), $VisitedNodeUuids );
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
  protected $Nodes = [];

  /**
   *
   * @var array
   */
  protected $ChangeLogs = [];

  /**
   *
   * @var resource 
   */
  protected $Fp;

}

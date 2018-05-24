<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
class Root extends Node implements \qck\GraphStorage\Loader
{

  function __construct( $Id, $DataDir )
  {
    parent::__construct( $Id );
    $this->DataDir = $DataDir;
  }

  function save()
  {
    $this->saveRecursive( $this );
    $this->LastSaveTime = time();
  }

  protected function saveRecursive( Node $Node, array &$VisitedNodeUuids = [] )
  {
    $Uuid = $Node->getUuid();
    if ( in_array( $Uuid, $VisitedNodeUuids ) )
      return;
    $VisitedNodeUuids[] = $Uuid;

    $RawData = $Node->getRawData();
    $SaveNode = $Node->getModifiedTime() >= $this->LastSaveTime;
    foreach ( $RawData as $key => $value )
    {
      if ( $value instanceof UuidObject )
      {
        if ( $value instanceof Node )
          $this->saveRecursive( $value );
        if ( $SaveNode )
          $value = new UuidObject( $value->getUuid() );
        $RawData[ $key ] = $value;
      }
    }
    $this->saveToFile( $Uuid, $RawData );
  }

  protected function saveToFile( $Uuid, array $RawData )
  {
    $File = $this->getFilePath( $Uuid );
    file_put_contents( $File, serialize( $RawData ), LOCK_EX );
  }

  protected function getFilePath( $Uuid )
  {
    return $this->DataDir . DIRECTORY_SEPARATOR . $Uuid . ".dat";
  }

  function load( $Uuid )
  {
    $File = $this->getFilePath( $Uuid );
    $Node = unserialize( file_exists( $File ) ? file_get_contents( $File ) : null );
    /* @var $Node Node */
    if ( $Node instanceof Node )
    {
      $Node->setLoader( $this );
      return $Node;
    }
    return null;
  }

  protected $LastSaveTime = 0;

  /**
   *
   * @var Loader 
   */
  protected $Loader;

  /**
   *
   * @var string
   */
  protected $DataDir;

}

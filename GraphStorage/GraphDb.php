<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
class GraphDb implements NodeLoader
{

  function __construct( $DataDir )
  {
    $this->DataDir = $DataDir;
  }

  function register( PersistableNode $Node )
  {
    $this->registerRecursively( $Node );
  }

  function commit()
  {
    /* @var $Node PersistableNode */
    foreach ( $this->Nodes as $Uuid => $Node )
    {
      $File = $this->getFilePath( $Uuid );
      if ( file_exists( $File ) && $Node->getModifiedTime() <= filemtime( $File ) )
        continue;
      // go through the raw data to traverse the graph and collect the data
      $Data = $Node->getData();
      foreach ( $Node->getData() as $key => $value )
        if ( $value instanceof UuidProvider )
          $Data[$key] = new NodeLazyLoader( $value->getUuid() );
      $SaveNode = clone $Node;
      $SaveNode->setData( $Data );

      file_put_contents( $File, serialize( $SaveNode ), LOCK_EX );
    }
  }

  function load( $Uuid )
  {
    $File = $this->getFilePath( $Uuid );
    /* @var $ExistingNode PersistableNode */
    $ExistingNode = isset( $this->Nodes[ $Uuid ] ) ? $this->Nodes[ $Uuid ] : null;
    if ( $ExistingNode && $ExistingNode->getModifiedTime() <= filemtime( $File ) )
      return $ExistingNode;

    $LoadedNode = file_exists( $File ) ? unserialize( file_get_contents( $File ) ) : null;

    if ( $LoadedNode instanceof PersistableNode )
    {
      if ( $ExistingNode )
      {
        $ExistingNode->setData( $LoadedNode->getData() );
        $ExistingNode->setModifiedTime( $LoadedNode->getModifiedTime() );
        return $ExistingNode;
      }
      else
      {
        $this->Nodes[ $Uuid ] = $LoadedNode;
        foreach ( $LoadedNode->getData() as $Value )
          if ( $Value instanceof NodeLazyLoader )
            $Value->setNodeLoader( $this );
        return $LoadedNode;
      }
    }
    return null;
  }

  public function delete( $Uuid )
  {
    $File = $this->getFilePath( $Uuid );
    if ( file_exists( $File ) )
      unlink( $File );
    if ( isset( $this->Nodes[ $Uuid ] ) )
      unset( $this->Nodes[ $Uuid ] );
  }

  protected function registerRecursively( PersistableNode $Node, array &$Visited = [] )
  {
    $Uuid = $Node->getUuid();
    if ( in_array( $Uuid, $Visited ) )
      return;
    $Visited[] = $Uuid;

    $this->Nodes[ $Uuid ] = $Node;

    foreach ( $Node->getData() as $value )
      if ( $value instanceof PersistableNode )
        $this->registerRecursively( $value, $Visited );
  }

  protected function getFilePath( $Uuid )
  {
    return $this->DataDir . DIRECTORY_SEPARATOR . $Uuid . ".dat";
  }

  protected $Nodes = [];

  /**
   *
   * @var string
   */
  protected $DataDir;

}

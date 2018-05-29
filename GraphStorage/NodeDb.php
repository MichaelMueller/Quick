<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
abstract class NodeDb
{

  /**
   * recursively register nodes
   * @param \qck\GraphStorage\PersistableNode $Node
   */
  abstract function commit();

  /**
   * @return PersistableNode
   */
  abstract function load( $Fqcn, $Id );

  /**
   * recursively register nodes
   * @param \qck\GraphStorage\PersistableNode $Node
   */
  function register( PersistableNode $Node )
  {
    $this->registerRecursively( $Node );
  }

  protected function registerRecursively( PersistableNode $Node, array &$Visited = [] )
  {
    if ( in_array( $Node, $Visited, true ) )
      return;
    $Visited[] = $Node;

    $this->Nodes[] = $Node;

    foreach ( $Node->getData() as $value )
      if ( $value instanceof PersistableNode )
        $this->registerRecursively( $value, $Visited );
  }

  protected function findNode( $Fqcn, $Id )
  {
    /* @var $Node Node */
    foreach ( $this->Nodes as $Node )
      if ( $Node->getId() == $Id && $Node->getFqcn() == $Fqcn )
        return $Node;
    return null;
  }

  protected $Nodes = [];

}

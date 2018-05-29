<?php

namespace qck\Data\Abstracts;

use \qck\Data\Interfaces\PersistableNode;

/**
 *
 * @author muellerm
 */
abstract class NodeDb implements \qck\Data\Interfaces\NodeDb
{

  /**
   * recursively register nodes
   * @param PersistableNode $Node
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

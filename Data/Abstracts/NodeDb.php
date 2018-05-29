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
    foreach ( $this->Nodes as $Node )
      if ( $Node->getFqcn() == $Fqcn && $Node->getId() == $Id )
        return $Node;

    return null;
  }

  protected function forgetNode( $Fqcn, $Id )
  {
    $index = 0;
    for ( $index = 0; $index < count( $this->Nodes ); $index++ )
      if ( $this->Nodes[ $index ]->getFqcn() == $Fqcn && $this->Nodes[ $index ]->getId() == $Id )
        break;
    if ( $index < count( $this->Nodes ) )
      unset( $this->Nodes[ $index ] );
  }

  protected $Nodes = [];

}

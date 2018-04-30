<?php

namespace qck\db\abstracts;

/**
 *
 * @author muellerm
 */
abstract class NodeDb implements \qck\db\interfaces\NodeDb
{

  public function add( interfaces\Node $Node )
  {
    $this->addRecursive( $Node );
  }

  protected function addRecursive( \qck\db\interfaces\Node $Node )
  {
    if ( in_array( $Node, $this->Nodes, true ) )
      return;

    $this->Nodes[ $Node->getUuid() ] = $Node;
    foreach ( $Node->keys() as $key )
    {
      $val = $Node->get( $key, false );
      if ( $val instanceof interfaces\Node )
        $this->addRecursive( $val );
    }
  }

  /**
   *
   * @var array 
   */
  protected $Nodes = [];

}

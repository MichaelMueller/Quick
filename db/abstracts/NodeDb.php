<?php

namespace qck\db\abstracts;

/**
 *
 * @author muellerm
 */
abstract class NodeDb implements \qck\db\interfaces\NodeDb
{

  public function add( \qck\db\interfaces\Node $Node )
  {
    $this->addRecursive( $Node );
  }

  protected function addRecursive( \qck\db\interfaces\Node $Node )
  {
    if ( in_array( $Node, $this->Nodes, true ) )
      return;

    $this->Nodes[ $Node->getUuid() ] = $Node;
    foreach ( $Node->getData() as $value )
      if ( $value instanceof \qck\db\interfaces\Node )
        $this->addRecursive( $value );
  }

  /**
   *
   * @var array 
   */
  protected $Nodes = [];

}

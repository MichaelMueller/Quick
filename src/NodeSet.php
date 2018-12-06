<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class NodeList implements Interfaces\NodeList
{

  public function add( Interfaces\Node $Node )
  {
    $this->Data[] = $Node;
    $this->Version++;
  }

  public function at( $Index )
  {
    return $this->Data[ $Index ];
  }

  public function removeAt( $Index )
  {
    unset( $this->Data[ $Index ] );
    $this->Version++;
  }

  public function size()
  {
    return count( $this->Data );
  }
}

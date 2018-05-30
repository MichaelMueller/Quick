<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectList extends Abstracts\Object implements Interfaces\ObjectList
{

  public function add( Interfaces\Object $Object )
  {
    $this->Data[] = $Object;
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

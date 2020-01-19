<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class ArrayNode implements Interfaces\DataNode
{

  public function add( Interfaces\DataNode $Node )
  {
    $this->Data[]  = $Node;
    $this->Changed = true;
  }

  public function at( $Index )
  {
    $Value              = $this->Data[ $Index ];
    if ( $Value instanceof NodeLoader )
      $this->Data[ $Index ] = $Value->load();
    return $this->Data[ $Index ];
  }

  public function removeAt( $Index )
  {
    unset( $this->Data[ $Index ] );
    $this->Changed = true;
  }

  public function size()
  {
    return count( $this->Data );
  }

  function getData()
  {
    return $this->Data;
  }

  function hasChanged()
  {
    return $this->Changed;
  }

  function setData( $Data )
  {
    $this->Data    = $Data;
    $this->Changed = true;
  }

  function setUnchanged()
  {
    $this->Changed = false;
  }

  protected $Data;
  protected $Changed;

}

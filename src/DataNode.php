<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class DataNode implements Interfaces\Node
{

  function keys()
  {
    return array_keys( $this->Data );
  }

  function __get( $key )
  {

    $Value = isset( $this->Data[ $key ] ) ? $this->Data[ $key ] : null;
    if ( $Value instanceof NodeLoader )
    {
      $this->Data[ $key ] = $Value->load();
      $Value              = $this->Data[ $key ];
    }
    return $Value;
  }

  function __set( $key, $value )
  {
    $this->Data[ $key ] = $value;
    $this->Changed      = true;
  }

  public function hasChanged()
  {
    return $this->Changed;
  }

  public function setUnchanged()
  {
    $this->Changed = false;
  }

  function getData()
  {
    return $this->Data;
  }

  function setData( $Data )
  {
    $this->Data    = $Data;
    $this->Changed = true;
  }

  /**
   *
   * @var array the actual data
   */
  protected $Data    = [];
  protected $Changed = false;

}

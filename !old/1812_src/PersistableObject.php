<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class PersistableObject implements Interfaces\PersistableObject
{

  function __get( $key )
  {
    $Value = isset( $this->Data[ $key ] ) ? $this->Data[ $key ] : null;
    if ( $Value && is_callable( $Value ) )
    {
      $Value              = $Value();
      $this->Data[ $key ] = $Value;
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

  function getId()
  {
    return $this->Id;
  }

  function setId( $Id )
  {
    $this->Id = $Id;
  }

  protected $Id;

  /**
   *
   * @var array the actual data
   */
  protected $Data    = [];
  protected $Changed = false;

}

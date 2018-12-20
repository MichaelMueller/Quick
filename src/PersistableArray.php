<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class PersistableArray implements Interfaces\PersistableObject
{

  function add( $Value )
  {
    $this->Data[]  = $Value;
    $this->Changed = true;
  }
  
  function indexes()
  {
    return array_keys($this->Data);
  }

  function at( $Index )
  {
    return $this->Data[ $Index ];
  }

  function remove( $Index )
  {
    unset( $this->Data[ $Index ] );
    $this->Changed = true;
  }

  function getSize()
  {
    return count( $this->Data );
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

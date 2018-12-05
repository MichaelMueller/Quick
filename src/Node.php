<?php

namespace qck\Data2\Abstracts;

/**
 *
 * @author muellerm
 */
class Node
{

  function getUuid()
  {
    
  }
  
  public function get( $Key )
  {
    return isset( $this->Data[ $Key ] ) ? $this->Data[ $Key ] : null;
  }

  public function set( $Key, $Value )
  {
    $this->Data[ $Key ] = $Value;

    $this->Version++;
  }  
  
  function getData()
  {
    return $this->Data;
  }

  function setData( array $Data )
  {
    $this->Data = $Data;
    $this->Version++;
  }

  function setId( $Id )
  {
    $this->Id = $Id;
  }

  public function getFqcn()
  {
    return get_class( $this );
  }

  function getVersion()
  {
    return $this->Version;
  }

  function setVersion( $Version )
  {
    $this->Version = $Version;
  }

  /**
   *
   * @var array the actual data
   */
  protected $Data = [];

}

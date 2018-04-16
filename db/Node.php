<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Node implements interfaces\Node
{

  public function __construct( interfaces\Backend $Backend, array $Data = array () )
  {
    $this->Backend = $Backend;
    foreach ( $Data as $key => $value )
      $this->$key = $value;
  }

  function __set( $key, $value )
  {
    $this->assertHasData();
    $this->Data[ $key ] = $value;
  }

  function __get( $key )
  {
    $this->assertHasData();
    return isset( $this->Data[ $key ] ) ? $this->Data[ $key ] : null;
  }

  function keys()
  {
    $this->assertHasData();
    return array_keys( $this->Data );
  }

  function add( $value )
  {
    $this->assertHasData();
    $this->Data[] = $value;
  }

  function has( $value )
  {
    $this->assertHasData();
    return in_array( $value, $this->Data );
  }

  function getData()
  {
    $this->assertHasData();
    return $this->Data;
  }

  function dropData()
  {
    $this->Data = null;
  }

  protected function assertHasData()
  {
    if ( $this->Data != null )
      return;
    $this->Data = $this->Backend->loadData( $this );
    if ( !$this->Data )
      $this->Data = [];
  }

  /**
   *
   * @var interfaces\Backend
   */
  protected $Backend;
  protected $Data;

}

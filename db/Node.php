<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Node implements interfaces\Node
{

  public function __construct( array $Data = array () )
  {
    foreach ( $Data as $key => $value )
      $this->$key = $value;
  }

  function __set( $key, $value )
  {
    $this->assertHasData();
    $prevVal = isset( $this->Data[ $key ] ) ? $this->Data[ $key ] : null;
    $this->Data[ $key ] = $value;
    /* @var $Observer interfaces\Observer */
    foreach ( $this->Observer as $Observer )
      $Observer->changed( $this, $key, $value, $prevVal );
  }

  function traverse( interfaces\Visitor $Visitor, array &$VisitedNodes = [] )
  {
    if ( in_array( $this, $VisitedNodes ) )
      return;

    $VisitedNodes[] = $this;
    $Visitor->handle( $this );
    $this->assertHasData();
    foreach ( $this->Data as $value )
    {
      if ( $value instanceof interfaces\Node )
        $value->traverse( $Visitor, $VisitedNodes );
    }
  }

  function dropData()
  {
    $this->Data = null;
  }

  function addObserver( interfaces\Observer $Observer )
  {
    $this->Observer[] = $Observer;
  }

  function setLoader( interfaces\Loader $Loader )
  {
    $this->Loader = $Loader;
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
    $newIndex = count( $this->Data );
    while ( isset( $this->Data[ $newIndex ] ) )
      $newIndex++;
    $this->$newIndex = $value;
  }

  function has( $value )
  {
    $this->assertHasData();
    return in_array( $value, $this->Data );
  }

  function hasData()
  {
    return $this->Data != null;
  }

  function getData()
  {
    $this->assertHasData();
    return $this->Data;
  }

  protected function assertHasData()
  {
    if ( $this->Data != null )
      return;
    if ( $this->Loader )
      $this->Data = $this->Loader->loadData( $this );
    else
      $this->Data = [];
  }

  /**
   *
   * @var interfaces\Loader
   */
  protected $Loader;

  /**
   *
   * @var array of Observer
   */
  protected $Observer = [];
  protected $Data;

}

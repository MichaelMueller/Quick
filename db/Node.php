<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Node implements interfaces\Node
{

  function __construct( array $Data = [], $Uuid = null )
  {
    $this->Data = $Data;
    $this->Uuid = $Uuid;
  }

  public function set( $key, $value )
  {
    $valueModification = isset( $this->Data[ $key ] );
    $prevVal = null;
    if ( $valueModification )
      $prevVal = $this->Data[ $key ];

    $this->Data[ $key ] = $value;
    if ( $valueModification )
    /* @var $Observer interfaces\NodeObserver */
      foreach ( $this->Observer as $Observer )
        $Observer->modified( $this, $key, $value, $prevVal );
    else
      foreach ( $this->Observer as $Observer )
        $Observer->added( $this, $key, $value );
    $this->ModifiedTime = time();
  }

  function __set( $key, $value )
  {
    $this->set( $key, $value );
  }

  public function remove( $key )
  {
    if ( isset( $this->Data[ $key ] ) )
    {
      $value = $this->Data[ $key ];
      unset( $this->Data[ $key ] );
      /* @var $Observer interfaces\Observer */
      foreach ( $this->Observer as $Observer )
        $Observer->deleted( $this, $key, $value );
      $this->ModifiedTime = time();
    }
  }

  function add( $value )
  {
    $newIndex = count( $this->Data );
    while ( isset( $this->Data[ $newIndex ] ) )
      $newIndex++;
    $this->$newIndex = $value;
  }

  function addObserver( interfaces\NodeObserver $Observer )
  {
    $this->Observer[] = $Observer;
  }

  function __get( $key )
  {
    return $this->get( $key, true );
  }

  public function get( $key )
  {
    if ( isset( $this->Data[ $key ] ) )
    {
      $val = $this->Data[ $key ];
      if ( $val instanceof interfaces\NodeRef )
      {
        $val = $val->getNode();
        if ( $val == null )
          $this->set( $key, null );
      }
      return $val;
    }
    else
      return null;
  }

  function has( $key )
  {
    return isset( $this->Data[ $key ] );
  }

  function keys()
  {
    return array_keys( $this->Data );
  }

  public function getModifiedTime()
  {
    return $this->ModifiedTime;
  }

  function getUuid()
  {
    if ( !$this->Uuid )
      $this->Uuid = \Ramsey\Uuid\Uuid::uuid4();
    return $this->Uuid;
  }

  public function getData()
  {
    return $this->Data;
  }

  /**
   *
   * @var array the actual data
   */
  protected $Data;

  /**
   *
   * @var string
   */
  protected $Uuid;

  /**
   *
   * @var array of Observer
   */
  protected $Observer = [];

  /**
   *
   * @var int date of last modifcation to the Data array
   */
  protected $ModifiedTime = 0;

}

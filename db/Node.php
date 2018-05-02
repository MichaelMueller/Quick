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
        $Observer->keyModified( $this, $key, $value, $prevVal );
    else
      foreach ( $this->Observer as $Observer )
        $Observer->keyAdded( $this, $key, $value );
    $this->ModifiedTime = time();
  }

  function __set( $key, $value )
  {
    $this->set( $key, $value );
  }

  function removeWhere( callable $ValueComparator )
  {
    $keys = $this->findInternal( $ValueComparator, true );
    foreach ( $keys as $key )
      $this->remove( $key );
  }

  public function remove( $key )
  {
    if ( isset( $this->Data[ $key ] ) )
    {
      $value = $this->Data[ $key ];
      unset( $this->Data[ $key ] );
      /* @var $Observer interfaces\NodeObserver */
      foreach ( $this->Observer as $Observer )
        $Observer->keyDeleted( $this, $key, $value );
      $this->ModifiedTime = time();
    }
  }

  function addIfNotExists( $value )
  {
    if ( $this->contains( $value ) )
      return false;
    $this->add( $value );
    return true;
  }

  function add( $value )
  {
    $newIndex = $value instanceof interfaces\UuidProvider ? $value->getUuid() : \Ramsey\Uuid\Uuid::uuid4()->toString();
    $this->set( $newIndex, $value );
  }

  function addObserver( interfaces\NodeObserver $Observer )
  {
    $this->Observer[] = $Observer;
  }

  function __get( $key )
  {
    return $this->get( $key );
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
      $this->Uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
    return $this->Uuid;
  }

  public function getData()
  {
    return $this->Data;
  }

  public function contains( $value )
  {
    return $this->findFirst( function($other) use($value)
        {
          return $value === $other;
        }
        ) !== null;
  }

  function find( callable $ValueComparator )
  {
    return $this->findInternal( $ValueComparator, false );
  }

  protected function findInternal( callable $ValueComparator, $returnKeys = false )
  {
    $results = [];
    foreach ( $this->keys() as $key )
    {
      $value = $this->get( $key );
      if ( $ValueComparator( $value ) )
        $results[] = $returnKeys ? $key : $value;
    }

    return $results;
  }

  public function findFirst( callable $ValueComparator )
  {
    foreach ( $this->keys() as $key )
    {
      $value = $this->get( $key );
      if ( $ValueComparator( $value ) )
        return $value;
    }
    return null;
  }

  public function setData( array $Data )
  {
    foreach ( $Data as $key => $value )
    {
      $this->set( $key, $value );
    }
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

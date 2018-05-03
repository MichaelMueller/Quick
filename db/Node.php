<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Node implements interfaces\Node, interfaces\Matcher
{

  function __construct( $Uuid = null )
  {
    $this->Uuid = $Uuid;
  }

  function addObserver( interfaces\NodeObserver $Observer )
  {
    $this->Observer[] = $Observer;
  }

  function add( $value )
  {
    $newIndex = count( $this->Data );
    while ( $this->has( $newIndex ) )
      ++$newIndex;
    $this->set( $newIndex, $value );
  }

  public function set( $key, $value )
  {
    $prevVal = null;
    $modification = $this->has( $key );
    if ( $modification )
      $prevVal = $this->get( $key, false );
    $this->Data[ $key ] = $value;
    $this->ModifiedTime = time();
    if ( $modification )
      foreach ( $this->Observer as $Observer )
        $Observer->propertyModified( $this, $key, $prevVal );
    else
      foreach ( $this->Observer as $Observer )
        $Observer->propertyAdded( $this, $key );
  }

  function __set( $key, $value )
  {
    $this->set( $key, $value );
  }

  public function remove( interfaces\Matcher $Matcher, $resolveRefs = true )
  {
    foreach ( $this->find( $Matcher, $resolveRefs ) as $key )
    {
      unset( $this->Data[ $key ] );
      $this->ModifiedTime = time();
    }

    foreach ( $this->Observer as $Observer )
      $Observer->removeInvoked( $this, $Matcher );
  }

  public function merge( interfaces\Node $OtherNode )
  {
    foreach ( $OtherNode->keys() as $key )
      $this->set( $key, $OtherNode->get( $key, false ) );
  }

  public function get( $key, $resolveRef = true )
  {
    if ( isset( $this->Data[ $key ] ) )
    {
      $val = $this->Data[ $key ];
      if ( $val instanceof interfaces\NodeRef && $resolveRef )
        $val = $val->getNode();
      return $val;
    }
    else
      return null;
  }

  function __get( $key )
  {
    return $this->get( $key );
  }

  function has( $key )
  {
    return isset( $this->Data[ $key ] );
  }

  function keys()
  {
    return array_keys( $this->Data );
  }

  function find( interfaces\Matcher $Matcher, $resolveRefs = true )
  {
    $Matchings = [];
    foreach ( $this->keys() as $key )
      if ( $Matcher->matches( $this->get( $key, $resolveRefs ) ) )
        $Matchings[] = $key;
    return $Matchings;
  }

  function findFirst( interfaces\Matcher $Matcher, $resolveRefs = true )
  {
    $Matchings = [];
    foreach ( $this->keys() as $key )
      if ( $Matcher->matches( $this->get( $key, $resolveRefs ) ) )
        return $this->get( $key, $resolveRefs );
    return null;
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

  public function matches( $value )
  {
    return $value instanceof interfaces\UuidProvider && $value->getUuid() == $this->getUuid();
  }

  /**
   *
   * @var array the actual data
   */
  protected $Data = [];

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

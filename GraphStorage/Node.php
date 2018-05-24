<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
class Node
{

  function __construct( $Uuid = null )
  {
    parent::__construct( $Uuid );
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
    $Valid = is_scalar( $value ) || $value instanceof Node || $value instanceof NodeRef;
    if ( !$Valid )
      throw new \InvalidArgumentException( "Nodes can only contain scalar values or other Node or NodeRef Objects" );
    $this->Data[ $key ] = $value;
    $this->ModifiedTime = time();
  }

  public function remove( callable $Matcher )
  {
    foreach ( $this->find( $Matcher ) as $key )
    {
      unset( $this->Data[ $key ] );
      $this->ModifiedTime = time();
    }
  }

  function __set( $key, $value )
  {
    $this->set( $key, $value );
  }

  public function setData( array $Data )
  {
    foreach ( $Data as $key => $value )
      $this->set( $key, $value );
  }

  public function get( $key )
  {
    if ( isset( $this->Data[ $key ] ) )
    {
      $val = $this->Data[ $key ];
      if ( $val instanceof Uuid )
        $val = $this->Loader->load( $val->getUuid() );
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

  function getRawData()
  {
    return $this->Data();
  }

  function keys()
  {
    return array_keys( $this->Data );
  }

  function find( callable $Matcher )
  {
    return $this->findInternal( $Matcher, false );
  }

  function findFirst( callable $Matcher )
  {
    return $this->findInternal( $Matcher );
  }

  protected function findInternal( callable $Matcher, $findFirst = false )
  {
    $Matchings = [];
    foreach ( $this->keys() as $key )
    {
      if ( call_user_func( $Matcher, $this->get( $key ) ) === true )
      {
        if ( $findFirst )
          return $key;
        $Matchings[] = $key;
      }
    }
    return $findFirst ? null : $Matchings;
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

  function setLoader( Loader $Loader )
  {
    $this->Loader = $Loader;
  }

  /**
   *
   * @var array the actual data
   */
  protected $Data = [];

  /**
   *
   * @var Loader
   */
  protected $Loader = 0;

  /**
   *
   * @var int date of last modifcation to the Data array
   */
  protected $ModifiedTime = 0;

}

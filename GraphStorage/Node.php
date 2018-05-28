<?php

namespace qck\GraphStorage;

/**
 *
 * @author muellerm
 */
class Node implements PersistableNode
{

  function __construct( $Uuid = null )
  {
    $this->Uuid = $Uuid;
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
    $this->Data[ $key ] = $value;
    $this->ModifiedTime = time();
  }

  public function remove( callable $Matcher )
  {
    $deletedKeys = $this->findKeys( $Matcher );
    foreach ( $deletedKeys as $key )
      unset( $this->Data[ $key ] );
    if ( count( $deletedKeys ) > 0 )
      $this->ModifiedTime = time();
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
      if ( $this->Data[ $key ] instanceof UnloadedNode )
        $this->Data[ $key ] = $this->Data[ $key ]->load();
      return $this->Data[ $key ];
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

  function contains( $Value )
  {
    return $this->findValue(
            function($OtherValue) use ($Value)
        {
          return $OtherValue === $Value;
        } );
  }

  function findKeys( callable $Matcher )
  {
    return $this->findKeysInternal( $Matcher, false );
  }

  function findValue( callable $Matcher )
  {
    $Keys = $this->findKeys( $Matcher, true );
    return count( $Keys ) > 0 ? $this->get( $Keys[ 0 ] ) : null;
  }

  protected function findKeysInternal( callable $Matcher, $findFirst = false )
  {
    $Matchings = [];
    foreach ( $this->keys() as $key )
    {
      if ( call_user_func( $Matcher, $this->get( $key ) ) === true )
      {
        $Matchings[] = $key;
        if ( $findFirst )
          break;
      }
    }
    return $Matchings;
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

  public function getModifiedTime()
  {
    return $this->ModifiedTime;
  }

  public function setModifiedTime( $ModifiedTime )
  {
    $this->ModifiedTime = $ModifiedTime;
  }

  /**
   *
   * @var string
   */
  protected $Uuid;

  /**
   *
   * @var array the actual data
   */
  protected $Data = [];

  /**
   *
   * @var int
   */
  protected $ModifiedTime = -1;

}

<?php

namespace qck\db\interfaces;

/**
 * A Node is a special Object that is persistable within a Graph structure.
 * It basically provides a UUID for identification and for persisting references.
 * Internally an Object using the Node interface is like an array (key/value pairs).
 * An Object must only expose this interface to be used with NodeDb Objects.
 * 
 * @author muellerm
 */
interface Node extends UuidProvider
{

  /**
   * Will add an Observer to this Node. @see NodeObserver
   * @param \qck\db\interfaces\NodeObserver $Observer
   */
  function addObserver( NodeObserver $Observer );

  /**
   * add a value with random key. notify observer
   * @param mixed $value
   */
  function add( $value );

  /**
   * set a value with specific key. notify observer
   * @param string $key
   * @param mixed $value
   */
  function set( $key, $value );
  
  /**
   * will call set() in a foreach loop
   * @param array $Data
   */
  function setData( array $Data );

  /**
   * remove matching elements. notify observer
   * @param \qck\db\interfaces\Matcher $Matcher
   */
  function remove( Matcher $Matcher, $resolveRefs=true );
  
  /**
   * will set all values from $OtherNode on this
   * @param \qck\db\interfaces\Node $OtherNode
   */
  function merge( Node $OtherNode );  

  /**
   * get a value or null, if its of type NodeRef the ref will be resolved if $resolveRef is true
   * @param string $key
   * @param bool $resolveRef
   * @return mixed
   */
  function get( $key, $resolveRef = true );

  /**
   * @param string $key
   * @return bool true if key exists, false otherwise
   */
  function has( $key );

  /**
   * @param string $key
   * @return array
   */
  function keys();

  /**
   * @param \qck\db\interfaces\Matcher $Matcher
   * @return array an array of matching keys (!!!)
   */
  function find( Matcher $Matcher, $resolveRefs=true );
  
  /**
   * @param \qck\db\interfaces\Matcher $Matcher
   * @return mixed an array of matching keys (!!!)
   */
  function findFirst( Matcher $Matcher, $resolveRefs=true );

  /**
   * @return int The timestamp of last modificaton of the internal data
   */
  function getModifiedTime();
}

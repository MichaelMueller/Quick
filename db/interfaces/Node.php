<?php

namespace qck\db\interfaces;

/**
 * basically an array of data and a UUID
 * @author muellerm
 */
interface Node
{
  /// SETTER

  /**
   * add a value with a random key
   * @param type $value
   */
  function add( $value );

  /**
   * @return void
   */
  function set( $key, $value );

  /**
   * @param string $key
   */
  function remove( $key );

  /**
   * @param \qck\db\interfaces\Observer $Observer
   */
  function addObserver( NodeObserver $Observer );

  /**
   * @return mixed (also null if not found)
   */
  function get( $key );

  /**
   * @return array the data array of this node
   */
  function getData();
  
  /**
   * @param string $key
   * @return bool
   */
  function has( $key );

  /**
   * @return array of the current keys
   */
  function keys();

  /**
   * @return string a uuid. if none is set a new one is generated
   */
  function getUuid();

  /**
   * @return int The timestamp of last modificaiton of the data
   */
  function getModifiedTime();
}

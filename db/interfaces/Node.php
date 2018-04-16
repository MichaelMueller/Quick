<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface Node
{

  /**
   * @return void
   */
  function __set( $key, $value );

  /**
   * @return mixed (also null if not found)
   */
  function __get( $key );

  /**
   * @return array of the current keys
   */
  function keys();

  /**
   * @return void
   */
  function add( $value );

  /**
   * @return bool
   */
  function has( $value );

  /**
   * @return array
   */
  function getData();

  /**
   * @return void
   */
  function dropData();
  
  /**
   * @return Backend
   */
  function getBackend();
}

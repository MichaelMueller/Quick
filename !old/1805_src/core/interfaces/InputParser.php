<?php

namespace qck\core\interfaces;

/**
 * 
 * @author muellerm
 */
interface InputParser
{

  function at( $index, $default = null );

  function get( $key, $default = null );

  function postAt( $key, $default = null );

  function post( $index, $default = null );
}

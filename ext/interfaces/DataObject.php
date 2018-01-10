<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace qck\ext\interfaces;

/**
 *
 * @author muellerm
 */
interface DataObject
{
  function add( $key );
  
  function set( $key, $value );

  function remove( $key );
  
  function removeWhere( callable $Matcher );
  
  function keys();
  
  function has( $key );  
  
  function get( $key );
  
  function getWhere( callable $Matcher );

}

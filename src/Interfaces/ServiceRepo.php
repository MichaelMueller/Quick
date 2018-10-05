<?php

namespace Qck\Interfaces;

/**
 * Central class for storing an accessing service classes
 * @author muellerm
 */
interface ServiceRepo
{

  /**
   * 
   * @param object $Service
   */
  function addService( $Service );

  /**
   * 
   * @param string $Fqcn
   * @param \Qck\Interfaces\callable $Factory
   */
  function addServiceFactory( $Fqcn, callable $Factory );

  /**
   * get a service
   * @param string $Fqin the fully qualified interface name
   * @param string $Fqcn an optional ClassName if a specific instance is requested the function throws an exception
   * @return mixed a class implementing the requested interface, possibly matching $Fqcn or null
   */
  function getOptional( $Fqin, $Fqcn = null );

  /**
   * get a service
   * @param string $Fqin the fully qualified interface name
   * @param string $Fqcn an optional ClassName if a specific instance is requested the function throws an exception   
   * @throws \InvalidArgumentException if no instance could be found 
   * @return mixed a class implementing the requested interface, possibly matching $Fqcn or null
   */
  function get( $Fqin, $Fqcn = null );

  /**
   * create a class based on an interface if a factory can be found
   * @param string $Fqin the fully qualified interface name
   * @param string $Fqcn an optional ClassName if a specific instance is requested the function throws an exception   
   * @return mixed a class implementing the requested interface, possibly matching $Fqcn or null
   */
  function createOptional( $Fqin, $Fqcn = null );
  
  /**
   * create a class based on an interface if a factory can be found
   * @param string $Fqin the fully qualified interface name
   * @param string $Fqcn an optional ClassName if a specific instance is requested the function throws an exception   
   * @throws \InvalidArgumentException if no instance could be found
   * @return mixed a class implementing the requested interface, possibly matching $Fqcn
   */
  function create( $Fqin, $Fqcn = null );
  
  /**
   * 
   * @param string $Fqin
   * @return array an array of instances
   */
  function getAll( $Fqin );
}

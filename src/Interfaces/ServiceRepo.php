<?php

namespace Qck\Interfaces;

/**
 * Central class for storing an accessing service classes
 * @author muellerm
 */
interface ServiceRepo
{

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
   * @throws \InvalidArgumentException if no instance could be found and $ThrowIfNotFound is true
   * @return mixed a class implementing the requested interface, possibly matching $Fqcn or null
   */
  function get( $Fqin, $Fqcn = null );
}

<?php

namespace Qck\Interfaces;

/**
 * Encapsulation of everything that is known when a request is sent to this system (input
 * variables, env, client and config infos)
 * @author muellerm
 */
interface Arguments
{

  /**
   * Get input parameter either from post, cookie, get or cli
   * @param string $Name
   * @param mixed $Default
   * @return mixed
   */
  public function get( $Name, $Default = null );

  /**
   * 
   * @param string $Name
   * @return bool
   */
  public function has( $Name );

  /**
   * 
   * @return array
   */
  public function getData();
}

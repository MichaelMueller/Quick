<?php

namespace Qck\App\Interfaces;

/**
 * Service Class for the current Request
 * @author muellerm
 */
interface Request
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
  public function getParams();

  /**
   * @return bool
   */
  function isCli();

  /**
   * @return string the user agent
   */
  function getUserAgent();

  /**
   * @return bool whether the browser signature is known or not
   */
  function isKnownUserAgent();

  /**
   * @return string an IPv4, IPv6 address or false if no ip could be found
   */
  function getIp();
}

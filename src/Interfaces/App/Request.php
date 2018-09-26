<?php

namespace Qck\Interfaces;

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

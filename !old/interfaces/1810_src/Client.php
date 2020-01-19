<?php

namespace Qck\Interfaces;

/**
 * Client specific information goes here.
 * @author muellerm
 */
interface Client
{

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

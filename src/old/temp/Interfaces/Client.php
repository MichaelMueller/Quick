<?php

namespace Qck\Interfaces;

/**
 * Client specific information goes here.
 * @author muellerm
 */
interface Client
{

  const BROWSER_UNKNOWN = 0;
  const BROWSER_KNOWN   = 1;
  const BROWSER_IE      = 2;
  const BROWSER_FIREFOX = 3;
  const BROWSER_CHROME  = 4;
  const BROWSER_SAFARI  = 5;

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

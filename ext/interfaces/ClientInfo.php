<?php
namespace qck\ext\interfaces;

/**
 *
 * @author muellerm
 */
interface ClientInfo
{
  const BROWSER_UNKNOWN = 0;
  // means: the browser is known but too exotic
  const BROWSER_KNOWN = 1;
  const BROWSER_IE = 2;
  const BROWSER_FIREFOX = 3;
  const BROWSER_CHROME = 4;
  const BROWSER_SAFARI = 5;
   
  /**
   * @return int one of the above constants
   */  
  function getBrowser();
  /**
   * @return string an IPv4, IPv6 address or false if no ip could be found
   */  
  function getIp();
}

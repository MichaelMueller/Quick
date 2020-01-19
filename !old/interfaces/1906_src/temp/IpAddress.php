<?php

namespace Qck\Interfaces;

/**
 * Client specific information goes here.
 * @author muellerm
 */
interface IpAddress
{

  /**
   * @return string an IPv4, IPv6 address or false if no ip could be found
   */
  function getValue();
  
  /**
   * @return bool
   */
  function isValid();
}

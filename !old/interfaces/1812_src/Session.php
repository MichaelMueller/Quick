<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface Session
{

  /**
   * start session and return a username if set
   * @return string or null if none is set
   */
  function getUsername();

  /**
   * start session and set a username if set
   * @return string or null if none is set
   */
  function setUsername($Username);

  /**
   * completely clear session
   */
  function destroySession();
}

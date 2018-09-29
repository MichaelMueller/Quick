<?php

namespace Qck\App\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface Session
{

  /**
   * @return string or null if none is set
   */
  function getUserName();
}

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
   * @return string or null if none is set
   */
  function getUserId();
}

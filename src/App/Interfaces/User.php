<?php

namespace Qck\App\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface User
{

  /**
   * 
   * @param string $Route
   * @return bool
   */
  function hasPermissionFor( $Route );
}

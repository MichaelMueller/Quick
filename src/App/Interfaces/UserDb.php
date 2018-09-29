<?php

namespace Qck\App\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface UserDb
{

  /**
   * @return User
   */
  function getUser( $UserName );
}

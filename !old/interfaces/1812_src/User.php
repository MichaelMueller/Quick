<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface User
{

  /**
   * @return string
   */
  function getUsername();

  /**
   * @return string
   */
  function getHashedPassword();

  /**
   * @return Authenticator An Authenticator or null if no custom authenticator is used
   */
  function getAuthenticator();

  /**
   * @return bool
   */
  function isAdmin();
}

<?php

namespace qck\ext\interfaces;

/**
 * Session Management, calls to Pod::get() and Pod::set() will trigger
 * session start and validation
 *
 * @author muellerm
 */
interface Session
{

  /**
   * Will set a user id- this means a user is logged in and authenticated
   * A session id will be regenerated
   */
  function setUsername( $Username );

  /**
   * @return mixed The user id or null if none is set which means this user is not authenticated currently
   */
  function getUsername();

  /**
   * destory the session
   */
  function destroySession();
}

<?php

namespace qck\interfaces;

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
  function setUserId($id);

  /**
   * @return mixed The user id or null if none is set which means this user is not authenticated currently
   */
  function getUserId();
  
  /**
   * Will set a user id- this means a user is logged in and authenticated
   * A session id will be regenerated
   */
  function setUser(  User $User );

  /**
   * @return User The user or null if none is set which means this user is not authenticated currently
   */
  function getUser();
  
  /**
   * destory the session
   */
  function destroySession();
}

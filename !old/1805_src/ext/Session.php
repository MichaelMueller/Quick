<?php

namespace qck\ext;

/**
 * Session Management for a user
 *
 * @author muellerm
 */
class Session implements interfaces\Session
{

  const FP_KEY = "__FP__";
  const LAST_LOGIN_KEY = "__LT__";
  const USER_ID_KEY = "__ID__";

  function __construct( interfaces\ClientInfo $ClientInfo, $SessionDir,
                        $LoginTtlSecs = 600 )
  {
    $this->ClientInfo = $ClientInfo;
    $this->SessionDir = $SessionDir;
    $this->LoginTtlSecs = $LoginTtlSecs;
  }

  public function getUsername()
  {
    $this->start();
    return isset( $_SESSION[ self::USER_ID_KEY ] ) ? $_SESSION[ self::USER_ID_KEY ] : null;
  }

  public function setUsername( $id )
  {
    $this->start();
    session_regenerate_id( true );
    $_SESSION[ self::USER_ID_KEY ] = $id;
  }

  function destroySession()
  {
    $this->start();
    session_unset();
    session_destroy();
  }

  // will start a new session OR 
  protected function start()
  {
    // check ip and browser info first
    $filterFlags = FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6;
    $ip = $this->ClientInfo->getIp();
    $ip = filter_var( $ip, FILTER_VALIDATE_IP, $filterFlags );
    if ( $ip === false )
      throw new \Exception(
      "Cannot start session. Reason: Invalid IP detected. REMOTE_ADDR was: " . $_SERVER[ "REMOTE_ADDR" ], 401 );

    $browser = $this->ClientInfo->getBrowser();
    if ( $browser == interfaces\ClientInfo::BROWSER_UNKNOWN )
    {
      throw new \Exception(
      "Cannot start session. Reason: Invalid Browser detected. HTTP_USER_AGENT was: " . $_SERVER[ "HTTP_USER_AGENT" ], 401 );
    }

    // Ok, user delivers valid info for starting the session
    if ( session_id() == "" )
    {
      //session_set_cookie_params( $this->SessionTimeOutSecs );
      if ( $this->SessionDir )
      {
        if ( !file_exists( $this->SessionDir ) )
        {
          mkdir( $this->SessionDir, 0777, true );
        }
        session_save_path( $this->SessionDir );
      }
      // set session dir
      session_start();
    }

    // check if we have a logged in user, otherwise he was waiting at the login
    if ( isset( $_SESSION[ self::FP_KEY ] ) && isset( $_SESSION[ self::LAST_LOGIN_KEY ] ) && !isset( $_SESSION[ self::LAST_LOGIN_KEY ] ) )
    {
      unset( $_SESSION[ self::FP_KEY ] );
      unset( $_SESSION[ self::LAST_LOGIN_KEY ] );
    }

    // now check for changes if we had a session before or a timeout
    $currFp = md5( $ip . $_SERVER[ "HTTP_USER_AGENT" ] );
    $prevFp = isset( $_SESSION[ self::FP_KEY ] ) ? $_SESSION[ self::FP_KEY ] : null;
    if ( $prevFp )
    {
      if ( $prevFp != $currFp )
      {
        session_destroy();
        throw new \Exception( "Cannot start session. Reason: IP changed. Current is $currFp, previous was: $prevFp", 401 );
      }
    }

    // check timeout
    $currTime = time();
    $prevTime = isset( $_SESSION[ self::LAST_LOGIN_KEY ] ) ? $_SESSION[ self::LAST_LOGIN_KEY ] : null;
    if ( $prevTime )
    {
      if ( $prevTime + $this->LoginTtlSecs < $currTime )
      {
        session_destroy();
        throw new \Exception( "Session timeout", 440 );
      }
    }

    $_SESSION[ self::FP_KEY ] = $currFp;
    $_SESSION[ self::LAST_LOGIN_KEY ] = $currTime;
  }

  /**
   *
   * @var interfaces\ClientInfo
   */
  protected $ClientInfo;
  protected $SessionDir;
  protected $LoginTtlSecs; // 10 minutesx

}

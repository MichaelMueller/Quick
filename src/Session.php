<?php

namespace Qck;

/**
 * Session Management for a user
 *
 * @author muellerm
 */
class Session implements \Qck\Interfaces\Session
{

    function __construct( \Qck\Interfaces\IpAddress $IpAddress = null, \Qck\Interfaces\Browser $Browser = null, $SessionDir = null )
    {
        $this->IpAddress  = $IpAddress;
        $this->Browser    = $Browser;
        $this->SessionDir = $SessionDir;
    }

    public function getUsername()
    {
        $this->startOrRestart();
        return isset( $_SESSION[ "Username" ] ) ? $_SESSION[ "Username" ] : null;
    }

    public function startSession( $Username, $TimeOutSecs = 900 )
    {
        $this->startOrRestart();
        session_regenerate_id( true );
        $_SESSION[ "Username" ]    = $Username;
        $_SESSION[ "TimeOutSecs" ] = $TimeOutSecs;
    }

    function stopSession()
    {
        $this->startOrRestart();
        session_unset();
        session_destroy();
    }

    // will start a new session OR 
    protected function startOrRestart()
    {
        // if we have an active session: stop here
        if ( session_status() == PHP_SESSION_ACTIVE )
            return;

        // set the session dir if needed
        if ( $this->SessionDir )
        {
            if ( !file_exists( $this->SessionDir ) )
            {
                mkdir( $this->SessionDir, 0777, true );
            }
            session_save_path( $this->SessionDir );
        }

        // start the session
        session_start();

        // check ip and browser info first
        $FingerPrint = "";
        if ( $this->IpAddress )
        {
            if ( !$this->IpAddress->isValid() )
            {
                session_unset();
                session_destroy();
                throw new \Exception(
                        "Cannot start session. Reason: Invalid IP " . $this->IpAddress->getValue() . " detected", 403 );
            }
            else
                $FingerPrint = $this->IpAddress->getValue();
        }

        if ( $this->Browser )
        {
            if ( !$this->Browser->isKnownBrowser() )
            {
                session_unset();
                session_destroy();
                throw new \Exception(
                        "Cannot start session. Reason: Invalid Browser " . $this->Browser->getSignature() . " detected.", 403 );
            }
            else
                $FingerPrint .= $this->Browser->getSignature();
        }

        // now check for changes if we had a session before or a timeout
        $currFp = md5( $FingerPrint );
        $prevFp = isset( $_SESSION[ "FingerPrint" ] ) ? $_SESSION[ "FingerPrint" ] : null;
        if ( $prevFp )
        {
            if ( $prevFp != $currFp )
            {
                session_unset();
                session_destroy();
                throw new \Exception( "Cannot start session. Reason: IP changed. Current is $currFp, previous was: $prevFp", 401 );
            }
        }

        // check timeout
        $currTime = time();
        $prevTime = isset( $_SESSION[ "LastSessionStart" ] ) ? $_SESSION[ "LastSessionStart" ] : null;
        if ( $prevTime )
        {
            if ( $prevTime + $_SESSION[ "TimeOutSecs" ] < $currTime )
            {
                session_unset();
                session_destroy();
                throw new \Exception( "Session timeout", 440 );
            }
        }

        $_SESSION[ "FingerPrint" ]      = $currFp;
        $_SESSION[ "LastSessionStart" ] = $currTime;
    }

    /**
     *
     * @var \Qck\Interfaces\IpAddress
     */
    protected $IpAddress;

    /**
     *
     * @var \Qck\Interfaces\Browser
     */
    protected $Browser;

    /**
     *
     * @var string
     */
    protected $SessionDir;

}

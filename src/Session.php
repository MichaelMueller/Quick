<?php

namespace Qck;

/**
 * Session Management for a user
 *
 * @author muellerm
 */
class Session implements \Qck\Interfaces\Session
{

    function __construct( $SessionDir = null )
    {
        $this->SessionDir = $SessionDir;
    }

    public function getUsername()
    {
        $this->startOrRestart();

        // check timeout 
        if (!isset( $_SESSION["TimeOut"] ) || time() > $_SESSION["TimeOut"])
        {
            $this->stopSession();
            throw new \Exception( "Session timeout", Interfaces\HttpResponder::EXIT_CODE_UNAUTHORIZED );
        }

        return isset( $_SESSION["Username"] ) ? $_SESSION["Username"] : null;
    }

    public function startSession( $Username, $TimeOutSecs = 900 )
    {
        session_set_cookie_params( $TimeOutSecs );
        $this->startOrRestart();
        $_SESSION["Username"] = $Username;
        $_SESSION["TimeOut"] = time() + $TimeOutSecs;
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
        if (session_status() == PHP_SESSION_ACTIVE)
            return;

        // set the session dir if needed
        if ($this->SessionDir)
        {
            if (!file_exists( $this->SessionDir ))
            {
                mkdir( $this->SessionDir, 0777, true );
            }
            session_save_path( $this->SessionDir );
        }

        // start the session
        session_start();
    }

    /**
     *
     * @var string
     */
    protected $SessionDir;

}

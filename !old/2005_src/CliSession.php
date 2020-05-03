<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class CliSession implements \Qck\Interfaces\Session
{

    function __construct( $SessionDir, \Qck\Interfaces\Arguments $Arguments )
    {
        $this->SessionDir = $SessionDir;
        $this->Arguments = $Arguments;
    }

    function setSessionIdKey( $SessionIdKey )
    {
        $this->SessionIdKey = $SessionIdKey;
    }

    public function startSession( $Username, $TimeOutSecs = 900 )
    {
        $SessionId = session_create_id( $Username );
        $Path = $this->getFilePath( $SessionId );
        $TimeOut = time() + $TimeOutSecs;
        file_put_contents( $Path, serialize( [$Username, $TimeOut] ) );
        return $SessionId;
    }

    public function getUsername()
    {
        $SessionId = $this->Arguments->get( $this->SessionIdKey );
        $Path = $this->getFilePath( $SessionId );

        if (file_exists( $Path ))
        {
            $Data = unserialize( file_get_contents( $Path ) );
            if (time() > $Data[1])
            {
                unlink( $Path );
                return null;
            }
            else
                return $Data[0];
        }
        return null;
    }

    public function stopSession()
    {
        $SessionId = $this->Arguments->get( $this->SessionIdKey );
        $Path = $this->getFilePath( $SessionId );
        if (file_exists( $Path ))
            unlink( $Path );
    }

    protected function getFilePath( $SessionId )
    {
        return $this->SessionDir . "/" . $SessionId;
    }

    public function getSessionIdKey()
    {
        return $this->SessionIdKey;
    }

    /**
     *
     * @var string
     */
    protected $SessionDir;

    /**
     *
     * @var \Qck\Interfaces\Arguments
     */
    protected $Arguments;

    /**
     *
     * @var string 
     */
    protected $SessionIdKey = "SessionId";

}

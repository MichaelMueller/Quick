<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class SimpleSession implements \Qck\Interfaces\Session
{

    function __construct( $SessionDir, \Qck\Interfaces\HttpHeader $HttpHeader, \Qck\Interfaces\Arguments $Arguments )
    {
        $this->SessionDir = $SessionDir;
        $this->HttpHeader = $HttpHeader;
        $this->Arguments = $Arguments;
    }

    function setSessionIdKey( $SessionIdKey )
    {
        $this->SessionIdKey = $SessionIdKey;
    }

    function setPath( $Path )
    {
        $this->Path = $Path;
    }

    function setDomain( $Domain )
    {
        $this->Domain = $Domain;
    }

    public function startSession( $Username, $TimeOutSecs = 900 )
    {
        $SessionId = session_create_id( $Username );
        $Path = $this->getFilePath( $SessionId );
        file_put_contents( $Path, $Username );
        $this->HttpHeader->addCookie( $this->SessionIdKey, $SessionId, time() + $TimeOutSecs, $this->Path, $this->Domain, true, true );
    }

    public function getUsername()
    {
        $SessionId = $this->Arguments->get( $this->SessionIdKey );
        $Path = $this->getFilePath( $SessionId );

        if (file_exists( $Path ))
        {
            if (filemtime( $Path ) < time() - $this->TimeOut)
            {
                unlink( $Path );
                return null;
            }
            else
            {
                touch( $Path );
                return file_get_contents( $Path );
            }
        }
        return null;
    }

    public function stopSession()
    {
        $SessionId = $this->Arguments->get( $this->SessionIdKey );
        $Path = $this->getFilePath( $SessionId );
        if (file_exists( $Path ))
            unlink( $Path );
        $this->HttpHeader->addCookie( $this->SessionIdKey, "", time() - 3600 );
    }

    protected function getFilePath( $SessionId )
    {
        return $this->SessionDir . "/" . $SessionId;
    }

    /**
     *
     * @var string
     */
    protected $SessionDir;

    /**
     *
     * @var \Qck\Interfaces\HttpHeader
     */
    protected $HttpHeader;

    /**
     *
     * @var \Qck\Interfaces\Arguments
     */
    protected $Arguments;

    /**
     *
     * @var string 
     */
    protected $SessionIdKey = "PHPSESSID";

    /**
     *
     * @var string 
     */
    protected $Path = "";

    /**
     *
     * @var string 
     */
    protected $Domain = "";

}

<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class HttpHeader implements \Qck\Interfaces\HttpHeader
{

    public function addCookie( $Name, $Value = "", $Expires = 0, $Path = "", $Domain = "",
                               $Secure = false, $HttpOnly = false, $SameSite = "Strict" )
    {
        $this->Cookies[] = [ $Name, $Value, $Expires, $Path, $Domain, $Secure, $HttpOnly ];
    }

    public function addHeader( $HeaderString )
    {
        $this->Headers[] = $HeaderString;
    }

    public function send( $ExitCode = \Qck\Interfaces\HttpHeader::EXIT_CODE_OK )
    {
        http_response_code( $ExitCode );
        foreach ( $this->Cookies as $Cookie )
            setcookie( $Cookie[ 0 ], $Cookie[ 1 ], $Cookie[ 2 ], $Cookie[ 3 ], $Cookie[ 4 ], $Cookie[ 5 ], $Cookie[ 6 ] );

        foreach ( $this->Headers as $Header )
            header( $Header );
    }

    public function redirect( $Url, $ExitCode = \Qck\Interfaces\HttpHeader::EXIT_CODE_MOVED_PERMANENTLY )
    {
        $this->addHeader( "Location: " . $Url );
        $this->send( $ExitCode );
    }

    public function sendContent( Interfaces\HttpContent $HttpContent,
                                 $ExitCode = \Qck\Interfaces\HttpHeader::EXIT_CODE_OK )
    {
        $this->send( $ExitCode );

        header( sprintf( "Content-Type: %s; charset=%s", $HttpContent->getContentType(), $HttpContent->getCharset() ) );
        $Content = strval($HttpContent);
        echo $Content;
    }

    /**
     *
     * @var array[string] 
     */
    protected $Headers = [];

    /**
     *
     * @var array[string] 
     */
    protected $Cookies = [];

}

<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class HttpHeader implements \Qck\Interfaces\HttpHeader
{

    public function addCookie( $name, $value = "", $expires = 0, $path = "", $domain = "",
                               $secure = false, $httpOnly = false, $sameSite = "Strict" )
    {
        $options         = array (
            'expires'  => $expires,
            'path'     => $path,
            'domain'   => $domain, // leading dot for compatibility or use subdomain
            'secure'   => $secure, // or false
            'httponly' => $httpOnly, // or false
            'samesite' => $sameSite // None || Lax  || Strict
        );
        $this->cookies[] = [$name, $value, $options];
    }

    public function addHeader( $headerString )
    {
        $this->headers[] = $headerString;
    }

    public function send( $exitCode = \Qck\Interfaces\HttpHeader::EXIT_CODE_OK )
    {
        http_response_code( $exitCode );
        foreach ($this->cookies as $Cookie)
            setcookie( $Cookie[0], $Cookie[1], $Cookie[2] );

        foreach ($this->headers as $Header)
            header( $Header );
    }

    public function redirect( $url, $exitCode = \Qck\Interfaces\HttpHeader::EXIT_CODE_MOVED_PERMANENTLY )
    {
        $this->addHeader( "Location: " . $url );
        $this->send( $exitCode );
    }

    function sendContent( Interfaces\HttpContent $content, $exitCode = \Qck\Interfaces\HttpHeader::EXIT_CODE_OK )
    {
        $this->send( $exitCode );

        header( sprintf( "Content-Type: %s; charset=%s", $content->contentType(), $content->charset() ) );
        $contents = $content();
        echo $contents;
    }

    /**
     *
     * @var array[string] 
     */
    protected $headers = [];

    /**
     *
     * @var array[string] 
     */
    protected $cookies = [];

}

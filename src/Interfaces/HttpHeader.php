<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface HttpHeader
{

    const EXIT_CODE_OK = 200;
    const EXIT_CODE_BAD_REQUEST = 400;
    const EXIT_CODE_UNAUTHORIZED = 401;
    const EXIT_CODE_FORBIDDEN = 403;
    const EXIT_CODE_NOT_FOUND = 404;
    const EXIT_CODE_UNPROCESSABLE_ENTITY = 422;
    const EXIT_CODE_INTERNAL_ERROR = 500;
    const EXIT_CODE_NOT_IMPLEMENTED = 501;
    const EXIT_CODE_MOVED_PERMANENTLY = 301;
    const EXIT_CODE_REDIRECT_FOUND = 302;

    /**
     * adds a 
     * 
     * @param string $HeaderString
     */
    function addHeader( $HeaderString );

    /**
     * will create a header for setting a cookie
     * 
     * @param string $Name
     * @param string $Value
     * @param string $Expires
     * @param string $Path
     * @param string $Domain
     * @param bool $Secure
     * @param bool $HttpOnly
     */
    function addCookie( $Name, $Value = "", $Expires = 0, $Path = "", $Domain = "", $Secure = false,
            $HttpOnly = false );

    /**
     * sends the HttpHeader contents: first the response code, then the cookies, then the rest
     */
    function send( $ExitCode = \Qck\Interfaces\HttpHeader::EXIT_CODE_OK );

    /**
     * will issue send() and finally send a redirect header
     * 
     * @param string $Url
     */
    function sendRedirect( $Url );

    /**
     * sends the http header, then sends the content type and encoding header, then sends the contents 
     */
    function sendContent( HttpContent $HttpContent, $ExitCode = \Qck\Interfaces\HttpHeader::EXIT_CODE_OK );
}

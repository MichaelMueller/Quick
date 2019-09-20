<?php

namespace Qck\Demo\AuthenticationDemo;

/**
 * 
 * @author muellerm
 */
class App extends \Qck\App
{

    /**
     * 
     * @return \Qck\HttpHeader
     */
    function getHttpHeader()
    {
        static $HttpHeader = null;
        if (is_null( $HttpHeader ))
            $HttpHeader = new \Qck\HttpHeader();
        return $HttpHeader;
    }

    /**
     * 
     * @return \Qck\Authenticator
     */
    function getAuthenticator()
    {
        static $Authenticator = null;
        if (is_null( $Authenticator ))
            $Authenticator = new \Qck\Authenticator( $this->getUserDb(), $this->getPasswordHasher(), $this->getSession() );
        return $Authenticator;
    }

    /**
     * 
     * @return \Qck\Session
     */
    function getSession()
    {
        static $Session = null;
        if (is_null( $Session ))
            $Session = new \Qck\Session( sys_get_temp_dir() );
        return $Session;
    }

    /**
     * 
     * @return \Qck\Authenticator
     */
    function getUserDb()
    {
        static $UserDb = null;
        if (is_null( $UserDb ))
            $UserDb = new \Qck\Demo\AuthenticationDemo\UserDb( $this->getPasswordHasher() );
        return $UserDb;
    }

    /**
     * 
     * @return \Qck\PasswordHasher
     */
    function getPasswordHasher()
    {
        static $PasswordHasher = null;
        if (is_null( $PasswordHasher ))
            $PasswordHasher = new \Qck\PasswordHasher();
        return $PasswordHasher;
    }

    /**
     * 
     * @param string $SubTitle
     * @param \Qck\Interfaces\HtmlSnippet $ContentSnippet
     * @return \Qck\HtmlPage
     */
    function createHtmlPage( $SubTitle, \Qck\Interfaces\HtmlSnippet $ContentSnippet )
    {
        $Page = new \Qck\HtmlPage( $this->getName() . " - " . $SubTitle, $ContentSnippet );
        return $Page;
    }

    function getName()
    {
        return "AuthenticationDemo";
    }

}

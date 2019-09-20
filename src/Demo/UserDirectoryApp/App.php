<?php

namespace Qck\Demo\UserDirectoryApp;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class App extends \Qck\App
{

    function __construct( \Qck\Interfaces\Arguments $Arguments, $ShowErrors = false )
    {
        parent::__construct( new \Qck\Route( "loginForm", AppFunctions\LoginForm::class ), $Arguments, $ShowErrors );
    }

    /**
     * 
     * @staticvar \Qck\HttpHeader $HttpHeader
     * @return \Qck\HttpHeader
     */
    function createHttpHeader()
    {
        return new \Qck\HttpHeader ();
    }

    function createHtmlPage( $SubTitle, \Qck\Interfaces\HtmlSnippet $ContentSnippet )
    {
        $Page = new \Qck\HtmlPage( $this->getName() . " - " . $SubTitle, $ContentSnippet );
        return $Page;
    }

    function getName()
    {
        return "UserDirectoryApp";
    }

}

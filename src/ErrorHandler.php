<?php

namespace Qck;

/**
 * A basic error handler
 * 
 * @author muellerm
 */
class ErrorHandler
{

    function setDevMode( $DevMode )
    {
        $this->DevMode = $DevMode;
    }

    function setHttpRequest( $HttpRequest )
    {
        $this->HttpRequest = $HttpRequest;
    }

    function setHtmlRequested( $HtmlRequested )
    {
        $this->HtmlRequested = $HtmlRequested;
    }

    function setAdminMailer( Interfaces\AdminMailer $AdminMailer )
    {
        $this->AdminMailer = $AdminMailer;
    }

    function install()
    {
        // basic error setup
        error_reporting( E_ALL );

        ini_set( 'log_errors', intval( !$this->DevMode ) );
        ini_set( 'display_errors', intval( $this->DevMode ) );
        ini_set( 'html_errors', intval( $this->HtmlRequested ) );

        set_error_handler( array ( $this, "errorHandler" ) );
        set_exception_handler( array ( $this, "exceptionHandler" ) );
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( $Exception )
    {
        if ( $this->AdminMailer )
            $this->AdminMailer->sendToAdmin( strval( $Exception ) );
        if ( $this->HttpRequest )
        {
            $code = $Exception->getCode() === 0 ? Interfaces\HttpResponder::EXIT_CODE_INTERNAL_ERROR : $Exception->getCode();
            http_response_code( $Exception->getCode() );
        }
        throw $Exception;
    }

    /**
     *
     * @var bool
     */
    protected $DevMode = false;

    /**
     *
     * @var bool
     */
    protected $HttpRequest = true;

    /**
     *
     * @var bool
     */
    protected $HtmlRequested = true;

    /**
     *
     * @var Interfaces\AdminMailer
     */
    protected $AdminMailer;

}

<?php

namespace Qck;

/**
 * A basic error handler
 * 
 * @author muellerm
 */
class ErrorHandler
{

    function __construct( $DevMode, $HtmlErrors = true, Interfaces\AdminMailer $AdminMailer = null )
    {
        $this->DevMode     = $DevMode;
        $this->HtmlErrors  = $HtmlErrors;
        $this->AdminMailer = $AdminMailer;
    }

    function install()
    {
        // basic error setup
        error_reporting( E_ALL );

        ini_set( 'log_errors', intval( !$this->DevMode ) );
        ini_set( 'display_errors', intval( $this->DevMode ) );
        ini_set( 'html_errors', intval( $this->HtmlErrors ) );

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
        throw $Exception;
    }

    /**
     *
     * @var bool
     */
    protected $DevMode;

    /**
     *
     * @var bool
     */
    protected $HtmlErrors;

    /**
     *
     * @var Interfaces\AdminMailer
     */
    protected $AdminMailer;

}

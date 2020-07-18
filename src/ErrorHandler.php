<?php

namespace Qck;

/**
 * @author muellerm
 */
class ErrorHandler implements Interfaces\ErrorHandler
{

    function __construct( bool $ShowErrors, bool $IsHttpRequest )
    {
        $this->ShowErrors = $ShowErrors;
        $this->IsHttpRequest = $IsHttpRequest;
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( $Exception )
    {
        /* @var $Exception \Exception */
        if ( $this->IsHttpRequest )
            http_response_code( $Exception->getCode() );

        if ( $this->showErrors() == false )
            print "An error occured. If the problem persists, please contact the Administrator.";

        throw $Exception;
    }

    function install()
    {
        error_reporting( E_ALL );
        ini_set( 'log_errors', intval( $this->ShowErrors === false ) );
        ini_set( 'display_errors', intval( $this->ShowErrors ) );
        ini_set( 'html_errors', intval( $this->IsHttpRequest ) );

        set_error_handler( array( $this, "errorHandler" ) );
        set_exception_handler( array( $this, "exceptionHandler" ) );
    }

    /**
     *
     * @var bool
     */
    protected $IsHttpRequest;

    /**
     *
     * @var bool
     */
    protected $ShowErrors = false;

}

<?php

namespace Qck;

/**
 * @author muellerm
 */
class ErrorHandler implements Interfaces\HttpRequestDetector
{

    function __construct( bool $showErrors )
    {
        $this->showErrors = $showErrors;
        $this->install();
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( $Exception )
    {
        /* @var $Exception \Exception */
        if ( $this->isHttpRequest() )
            http_response_code( 500 );

        throw $Exception;
    }

    function install()
    {
        error_reporting( E_ALL );
        ini_set( 'log_errors', intval( $this->showErrors === false ) );
        ini_set( 'display_errors', intval( $this->showErrors ) );
        ini_set( 'html_errors', intval( $this->isHttpRequest() ) );

        set_error_handler( array ( $this, "errorHandler" ) );
        set_exception_handler( array ( $this, "exceptionHandler" ) );
    }

    public function isHttpRequest()
    {
        if ( is_null( $this->isHttpRequest ) )
            $this->isHttpRequest = !isset( $_SERVER[ "argv" ] ) || is_null( $_SERVER[ "argv" ] ) || is_string( $_SERVER[ "argv" ] );
        return $this->isHttpRequest;
    }

    /**
     *
     * @var bool
     */
    protected $showErrors = false;

    /**
     *
     * @var null|bool
     */
    protected $isHttpRequest;

}

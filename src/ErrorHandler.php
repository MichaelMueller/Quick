<?php

namespace Qck;

/**
 * @author muellerm
 */
class ErrorHandler
{

    function __construct( $showErrors = true, \Qck\Interfaces\HttpRequest $httpDetector = null, \Qck\Interfaces\AdminMailer $adminMailer = null )
    {
        $this->showErrors   = $showErrors;
        $this->httpDetector = $httpDetector;
        $this->adminMailer  = $adminMailer;
        $this->install();
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( $exception )
    {
        /* @var $exception \Exception */
        if ( $this->httpDetector && $this->httpDetector->valid() )
            http_response_code( $exception->getCode() );

        if ( $this->adminMailer )
            $this->adminMailer->sendToAdmin( "Exception", sprintf( "Exception occured: %s, trace: %s", strval( $exception ), $exception->getTraceAsString() ) );
        throw $exception;
    }

    function install()
    {
        if ( $this->previousErrorHandler && $this->previousExceptionHandler )
            return;
        error_reporting( E_ALL );
        ini_set( 'log_errors', intval( $this->showErrors === false ) );
        ini_set( 'display_errors', intval( $this->showErrors ) );
        ini_set( 'html_errors', intval( $this->httpDetector ? $this->httpDetector->valid() : false  ) );

        $this->previousErrorHandler     = set_error_handler( array ( $this, "errorHandler" ) );
        $this->previousExceptionHandler = set_exception_handler( array ( $this, "exceptionHandler" ) );
        $this->installed                = true;
    }

    function __destruct()
    {
        $this->uninstall();
    }

    function uninstall()
    {

        if ( $this->previousErrorHandler && $this->previousExceptionHandler )
        {
            set_error_handler( $this->previousErrorHandler );
            set_exception_handler( $this->previousExceptionHandler );
            $this->previousErrorHandler     = null;
            $this->previousExceptionHandler = null;
        }
    }

    /**
     *
     * @var bool
     */
    protected $showErrors;

    /**
     *
     * @var \Qck\Interfaces\HttpRequest
     */
    protected $httpDetector;

    /**
     *
     * @var \Qck\Interfaces\AdminMailer
     */
    protected $adminMailer;
    // state
    protected $previousErrorHandler;
    protected $previousExceptionHandler;

}

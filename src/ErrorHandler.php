<?php

namespace Qck;

/**
 * Default Error Handler
 * 
 * @author muellerm
 */
class ErrorHandler
{

    function __construct( \Qck\Interfaces\App $app, $showErrors = false )
    {
        $this->app = $app;
        $this->setupErrorHandling( $showErrors );
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( $exception )
    {
        /* @var $exception \Exception */
        if ( $this->app->request()->isHttp() )
        {
            $code = $exception->getCode() != 0 ? $exception->getCode() : Interfaces\HttpHeader::EXIT_CODE_INTERNAL_ERROR;
            http_response_code( $code );
        }

        if ( $this->app->adminMailer() )
            $this->app->adminMailer()->sendToAdmin( "Exception", sprintf( "Exception occured: %s, trace: %s", strval( $exception ), $exception->getTraceAsString() ) );

        throw $exception;
    }

    protected function setupErrorHandling( $showErrors )
    {
        if ( $this->errorHandler && $this->exceptionHandler )
            return;
        error_reporting( E_ALL );
        ini_set( 'log_errors', intval( $showErrors === false ) );
        ini_set( 'display_errors', intval( $showErrors ) );
        ini_set( 'html_errors', intval( $this->app->request()->isHttp() ) );
        $this->errorHandler     = set_error_handler( array ( $this, "errorHandler" ) );
        $this->exceptionHandler = set_exception_handler( array ( $this, "exceptionHandler" ) );
    }

    protected function revokeHandlers()
    {
        if ( !$this->errorHandler || !$this->exceptionHandler )
            return;
        set_error_handler( $this->errorHandler );
        set_exception_handler( $this->exceptionHandler );
        $this->errorHandler     = null;
        $this->exceptionHandler = null;
    }

    public function __destruct()
    {
        $this->revokeHandlers();
    }

    /**
     *
     * @var \Qck\Interfaces\App
     */
    protected $app;

    /**
     *
     * @var \Qck\Interfaces\AdminMailer
     */
    protected $adminMailer;

    // state

    /**
     *
     * @var callable
     */
    protected $errorHandler;

    /**
     *
     * @var callable
     */
    protected $exceptionHandler;

}

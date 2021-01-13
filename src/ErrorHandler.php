<?php

namespace Qck;

/**
 * A basic Error Handler
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class ErrorHandler
{

    static function new( ): ErrorHandler
    {
        return new ErrorHandler( );
    }

    function __construct()
    {
        error_reporting( E_ALL );
        ini_set( 'log_errors', 1 );
        ini_set( 'display_errors', 0 );
        ini_set( 'html_errors', 0 );
        $this->install();
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( \Throwable $exception )
    {
        /* @var $exception \Exception */
        if ( $this->request !== null && $this->request->isHttpRequest() )
        {
            $code = $exception instanceof Exception ? $exception->httpReturnCode() : \Qck\HttpResponse::EXIT_CODE_INTERNAL_ERROR;
            http_response_code( $code );
        }

        throw $exception;
    }

    function setRequest( Request $request ): ErrorHandler
    {
        $this->request = $request;
        ini_set( 'html_errors', intval( $request->isHttpRequest() ) );
        return $this;
    }

    function setShowErrors( bool $showErrors ): ErrorHandler
    {
        $this->showErrors = $showErrors;

        ini_set( 'log_errors', intval( $showErrors ) );
        ini_set( 'display_errors', intval( $showErrors ) );
        return $this;
    }

    function install(): void
    {
        if ( $this->errorHandler && $this->exceptionHandler )
            return;
        $this->errorHandler     = set_error_handler( array ( $this, "errorHandler" ) );
        $this->exceptionHandler = set_exception_handler( array ( $this, "exceptionHandler" ) );
    }

    protected function uninstall(): void
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
        $this->uninstall();
    }

    /**
     *
     * @var Request
     */
    protected $request;

    /**
     *
     * @var bool
     */
    protected $showErrors;

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

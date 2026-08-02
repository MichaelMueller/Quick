<?php

namespace Qck;

/**
 * A basic Error Handler
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class ErrorHandler
{

    static function new(): ErrorHandler
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
        if ( ob_get_level() > 0 )
            ob_end_clean();
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
        if ( $this->installed )
            return;
        // set_*_handler returns the previously installed handler, which may legitimately
        // be null. Track the installed state separately so uninstall() always restores.
        $this->previousErrorHandler     = set_error_handler( array ( $this, "errorHandler" ) );
        $this->previousExceptionHandler = set_exception_handler( array ( $this, "exceptionHandler" ) );
        $this->installed                = true;
    }

    /**
     * Restores the handlers that were active before install().
     *
     * Must be called explicitly: while installed, PHP holds a reference to this
     * object in the handler registry, so __destruct() does not run before shutdown.
     */
    public function uninstall(): void
    {
        if ( !$this->installed )
            return;
        set_error_handler( $this->previousErrorHandler );
        set_exception_handler( $this->previousExceptionHandler );
        $this->previousErrorHandler     = null;
        $this->previousExceptionHandler = null;
        $this->installed                = false;
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
     * @var bool
     */
    protected $installed = false;

    /**
     *
     * @var callable
     */
    protected $previousErrorHandler;

    /**
     *
     * @var callable
     */
    protected $previousExceptionHandler;

}

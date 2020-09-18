<?php

namespace Qck;

/**
 * Router class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
abstract class App implements \Qck\Interfaces\App
{

    /**
     * @return callable|null
     */
    abstract function createAppFunction( $route );

    function setRouteParamName( $routeParamName )
    {
        $this->routeParamName = $routeParamName;
    }

    function setAdminMailer( \Qck\Interfaces\AdminMailer $adminMailer ): void
    {
        $this->adminMailer = $adminMailer;
    }

    function arguments()
    {
        return $this->arguments;
    }

    function currentRoute()
    {
        if (is_null( $this->currentRoute ))
            $this->currentRoute = $this->arguments()->get( $this->routeParamName() );
        return $this->currentRoute;
    }

    function routeParamName()
    {
        return $this->routeParamName;
    }

    function buildUrl( $RouteName, array $QueryData = [] )
    {
        $CompleteQueryData = array_merge( $QueryData, [$this->routeParamName() => $RouteName] );
        return "?" . http_build_query( $CompleteQueryData );
    }

    function run( \Qck\Interfaces\Arguments $arguments, $showErrors = false )
    {
        $this->arguments = $arguments;
        $this->setupErrorHandling( $showErrors );
        $route           = $this->currentRoute();
        $function        = $this->createAppFunction( $route );
        if (is_null( $function ))
            throw new \Exception( "No function found for route \"" . $route . "\".",
                                  \Qck\Interfaces\HttpHeader::EXIT_CODE_NOT_FOUND );

        $function();
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( $exception )
    {
        /* @var $exception \Exception */
        if ($this->arguments->isHttpRequest())
            http_response_code( $exception->getCode() );

        if ($this->adminMailer)
            $this->adminMailer->sendToAdmin( "Exception", sprintf( "Exception occured: %s, trace: %s", strval( $exception ), $exception->getTraceAsString() ) );

        throw $exception;
    }

    protected function setupErrorHandling( $showErrors )
    {
        error_reporting( E_ALL );
        ini_set( 'log_errors', intval( $showErrors === false ) );
        ini_set( 'display_errors', intval( $showErrors ) );
        ini_set( 'html_errors', intval( $this->arguments->isHttpRequest() ) );
        set_error_handler( array ($this, "errorHandler") );
        set_exception_handler( array ($this, "exceptionHandler") );
    }

    /**
     *
     * @var \Qck\Interfaces\Arguments
     */
    protected $arguments;

    /**
     *
     * @var \Qck\Interfaces\AdminMailer
     */
    protected $adminMailer;

    /**
     *
     * @var string 
     */
    protected $routeParamName = "q";

    // state

    /**
     *
     * @var string 
     */
    private $currentRoute;

}

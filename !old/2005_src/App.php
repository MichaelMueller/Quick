<?php

namespace Qck;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class App implements Interfaces\App
{

    function __construct( Interfaces\AppFunctionFactory $RouteFactory, Interfaces\Arguments $Arguments, $ShowErrors = false )
    {
        $this->RouteFactory = $RouteFactory;
        $this->Arguments = $Arguments;
        $this->ShowErrors = $ShowErrors;
    }

    function getRouteParamKey()
    {
        return $this->RouteParamKey;
    }

    function setRouteParamKey( $RouteParamKey )
    {
        $this->RouteParamKey = $RouteParamKey;
    }

    function buildUrl( $RouteName, array $QueryData = [] )
    {
        $CompleteQueryData = array_merge( $QueryData, [$this->RouteParamKey => $RouteName] );
        return "?" . http_build_query( $CompleteQueryData );
    }

    function run()
    {
        $this->setupErrorHandling();
        $RouteName = $this->Arguments->get( $this->RouteParamKey );
        $AppFunction = $this->RouteFactory->createAppFunction( $RouteName );

        if (is_null( $AppFunction ))
            throw new \Exception( "No AppFunction found for Route \"" . $RouteName . "\".",
                    Interfaces\HttpHeader::EXIT_CODE_NOT_FOUND );

        $AppFunction->run( $this, $this->Arguments );
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( $Exception )
    {
        /* @var $Exception \Exception */
        if ($this->Arguments->isHttpRequest())
            http_response_code( $Exception->getCode() );

        if ($this->ShowErrors == false)
            print "An error occured. If the problem persists, please contact the Administrator.";

        throw $Exception;
    }

    protected function setupErrorHandling()
    {
        error_reporting( E_ALL );
        ini_set( 'log_errors', intval( $this->ShowErrors === false ) );
        ini_set( 'display_errors', intval( $this->ShowErrors ) );
        ini_set( 'html_errors', intval( $this->Arguments->isHttpRequest() ) );

        set_error_handler( array ($this, "errorHandler") );
        set_exception_handler( array ($this, "exceptionHandler") );
    }

    /**
     *
     * @var Interfaces\AppFunctionFactory
     */
    protected $RouteFactory;

    /**
     *
     * @var Interfaces\Arguments
     */
    protected $Arguments;

    /**
     *
     * @var bool
     */
    protected $ShowErrors;

    /**
     *
     * @var string
     */
    protected $RouteParamKey = "q";

}

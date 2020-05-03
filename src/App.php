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

    function __construct( Interfaces\AppFunctionFactory $AppFunctionFactory, Interfaces\Arguments $Arguments, bool $ShowErrors )
    {
        $this->AppFunctionFactory = $AppFunctionFactory;
        $this->Arguments          = $Arguments;
        $this->ShowErrors         = $ShowErrors;
    }

    function getArguments()
    {
        return $this->Arguments;
    }

    function showErrors()
    {
        return $this->ShowErrors;
    }

    function setShowErrors( $ShowErrors )
    {
        $this->ShowErrors = $ShowErrors;
    }

    function getCurrentRoute()
    {
        return $this->getArguments()->get( $this->getRouteParamKey() );
    }

    function getRouteParamKey()
    {
        return "q";
    }

    function buildUrl( $RouteName, array $QueryData = [] )
    {
        $CompleteQueryData = array_merge( $QueryData, [ $this->getRouteParamKey() => $RouteName ] );
        return "?" . http_build_query( $CompleteQueryData );
    }

    function run()
    {
        $this->setupErrorHandling();

        $RouteName   = $this->getCurrentRoute();
        $AppFunction = $this->AppFunctionFactory->createAppFunction( $RouteName );
        if ( is_null( $AppFunction ) )
            throw new \Exception( "No AppFunction found for Route \"" . $RouteName . "\".",
                                  Interfaces\HttpHeader::EXIT_CODE_NOT_FOUND );

        $AppFunction->run( $this );
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( $Exception )
    {
        /* @var $Exception \Exception */
        if ( $this->getArguments()->isHttpRequest() )
            http_response_code( $Exception->getCode() );

        if ( $this->showErrors() == false )
            print "An error occured. If the problem persists, please contact the Administrator.";

        throw $Exception;
    }

    protected function setupErrorHandling()
    {
        error_reporting( E_ALL );
        ini_set( 'log_errors', intval( $this->ShowErrors === false ) );
        ini_set( 'display_errors', intval( $this->ShowErrors ) );
        ini_set( 'html_errors', intval( $this->Arguments->isHttpRequest() ) );

        set_error_handler( array ( $this, "errorHandler" ) );
        set_exception_handler( array ( $this, "exceptionHandler" ) );
    }

    /**
     *
     * @var Interfaces\AppFunctionFactory
     */
    protected $AppFunctionFactory;

    /**
     *
     * @var Interfaces\Arguments
     */
    protected $Arguments;

    /**
     *
     * @var bool
     */
    protected $ShowErrors = false;

}

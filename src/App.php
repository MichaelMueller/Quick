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

    function __construct( Interfaces\Route $DefaultRoute, Interfaces\Arguments $Arguments, $ShowErrors = false )
    {
        $this->Routes[] = $DefaultRoute;
        $this->Arguments = $Arguments;
        $this->ShowErrors = $ShowErrors;
    }

    function addRoute( Interfaces\Route $Route )
    {
        $this->Routes[] = $Route;
    }

    function setRouteParamKey( $RouteParamKey )
    {
        $this->RouteParamKey = $RouteParamKey;
    }

    function buildUrl( $AppFunctionFqcn, array $QueryData = [] )
    {
        $CompleteQueryData = array_merge( $QueryData, [$this->RouteParamKey => $AppFunctionFqcn] );
        return "?" . http_build_query( $CompleteQueryData );
    }

    function run()
    {
        $this->setupErrorHandling();
        $RouteName = $this->Arguments->get( $this->RouteParamKey );
        $Route = null;
        if ($RouteName === null)
            $Route = $this->Routes[0];
        else
        {
            $Filter = function($Route) use($RouteName)
            {
                /* @var $Route Qck\Interfaces\Route */
                return $Route->getRouteName() === $RouteName;
            };
            $Routes = array_filter( $this->Routes, $Filter );
            if (count( $Routes ) > 0)
                $Route = $Routes[0];
        }
        if ($Route === null)
            throw new \Exception( "Route \"" . $RouteName . "\" not found.", 404 );

        $Fqcn = $Route->getAppFunctionFqcn();
        /* @var $AppFunction Qck\Interfaces\AppFunction */
        $AppFunction = new $Fqcn();
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
     * @var Interfaces\Route[]
     */
    protected $Routes;

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

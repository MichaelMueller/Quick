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

    function __construct( Interfaces\Route $DefaultRoute )
    {
        $this->Routes[] = $DefaultRoute;
    }

    function addRoute( Interfaces\Route $Route )
    {
        $this->Routes[] = $Route;
    }

    function buildUrl( $AppFunctionFqcn, array $QueryData = [] )
    {
        $CompleteQueryData = array_merge( [$this->AppFunctionFqcn => $AppFunctionFqcn], $QueryData );
        return "?" . http_build_query( $CompleteQueryData );
    }

    function run( Interfaces\Arguments $Args )
    {
        $this->setupErrorHandling();
        $RouteName = $Args->get( $this->RouteParamKey );
        $Route = null;
        if ($RouteName !== null)
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
        $AppFunction->run( $this, $Args );
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( $Exception )
    {
        /* @var $Exception \Exception */
        if (http_response_code !== false)
            http_response_code( $Exception->getCode() );

        throw $Exception;
    }

    function setAppFunctionFqcn( $AppFunctionFqcn )
    {
        $this->AppFunctionFqcn = $AppFunctionFqcn;
    }

    protected function setupErrorHandling()
    {
        error_reporting( E_ALL );
        ini_set( 'log_errors', intval( !$this->DevMode ) );
        ini_set( 'display_errors', intval( $this->DevMode ) );
        ini_set( 'html_errors', intval( !$this->getCliDetector()->isCli() ) );

        set_error_handler( array($this, "errorHandler") );
        set_exception_handler( array($this, "exceptionHandler") );
    }

    protected function getSingleton( $Name, callable $Factory )
    {
        if (!isset( $this->Singletons[$Name] ))
            $this->Singletons[$Name] = call_user_func( $Factory );
        return $this->Singletons[$Name];
    }

    /**
     *
     * @var Interfaces\Route[]
     */
    protected $Routes;

    /**
     *
     * @var string
     */
    protected $RouteParamKey = "q";

    /**
     *
     * @var array[object]
     */
    protected $Singletons;

}

<?php

namespace Qck;

/**
 * Router class maps arguments to specific functions
 * 
 * @author muellerm
 */
class Router implements \Qck\Interfaces\Router, Interfaces\Functor
{

    function __construct( \Qck\Interfaces\App $app, string $routeParamName = "q" )
    {
        $this->app            = $app;
        $this->routeParamName = $routeParamName;
    }

    function currentRoute()
    {
        if ( is_null( $this->currentRoute ) )
            $this->currentRoute = $this->app->request()->args()->get( $this->routeParamName() );
        return $this->currentRoute;
    }

    function routeParamName()
    {
        return $this->routeParamName;
    }

    function buildUrl( $routeName, array $queryData = [] )
    {
        $completeQueryData = array_merge( $queryData, [ $this->routeParamName() => $routeName ] );
        return "?" . http_build_query( $completeQueryData );
    }

    function __invoke()
    {
        $route    = $this->currentRoute();
        $function = $this->app->functorFactory()->createFunctor( $route );
        if ( is_null( $function ) )
            throw new \Exception( "No function found for route \"" . $route . "\".",
                                  \Qck\Interfaces\HttpHeader::EXIT_CODE_NOT_FOUND );

        $function();
    }

    /**
     *
     * @var \Qck\Interfaces\App
     */
    protected $app;

    /**
     *
     * @var string 
     */
    protected $routeParamName;

    // state

    /**
     *
     * @var string 
     */
    private $currentRoute;

}

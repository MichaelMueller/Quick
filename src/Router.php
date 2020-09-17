<?php

namespace Qck;

/**
 * Router class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class Router implements \Qck\Interfaces\Router
{

    static function createWithArguments( Interfaces\FunctionFactory $functionFactory, Interfaces\Arguments $arguments )
    {
        return new Router( $functionFactory, $arguments, null );
    }

    static function createWithRoute( Interfaces\FunctionFactory $functionFactory, $route = null )
    {
        return new Router( $functionFactory, null, $route );
    }

    protected function __construct( \Qck\Interfaces\FunctionFactory $functionFactory, \Qck\Interfaces\Arguments $arguments = null, $route = null )
    {
        $this->functionFactory = $functionFactory;
        $this->arguments       = $arguments;
        $this->currentRoute    = $route;
    }

    function arguments()
    {
        return $this->arguments;
    }

    function currentRoute()
    {
        if ( is_null( $this->currentRoute ) )
            $this->currentRoute = $this->getArguments()->get( $this->routeParamName() );
        return $this->currentRoute;
    }

    function routeParamName()
    {
        return "q";
    }

    function buildUrl( $RouteName, array $QueryData = [] )
    {
        $CompleteQueryData = array_merge( $QueryData, [ $this->getRouteParamKey() => $RouteName ] );
        return "?" . http_build_query( $CompleteQueryData );
    }

    function __invoke()
    {
        $route    = $this->getCurrentRoute();
        $function = $this->functionFactory->create( $route );
        if ( is_null( $function ) )
            throw new \Exception( "No function found for route \"" . $route . "\".",
                                  \Qck\Interfaces\HttpHeader::EXIT_CODE_NOT_FOUND );

        $function();
    }

    /**
     *
     * @var \Qck\Interfaces\FunctionFactory
     */
    protected $functionFactory;

    /**
     *
     * @var \Qck\Interfaces\Arguments
     */
    protected $arguments;

    // state

    /**
     *
     * @var string 
     */
    protected $currentRoute;

}

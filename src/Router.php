<?php

namespace Qck;

/**
 * Router class maps arguments to specific functions
 * 
 * @author muellerm
 */
class Router implements \Qck\Interfaces\Router, Interfaces\Functor
{

    function __construct( \Qck\Interfaces\Request $request, \Qck\Interfaces\FunctorFactory $functorFactory, string $routeParamName = "q" )
    {
        $this->request        = $request;
        $this->functorFactory = $functorFactory;
        $this->routeParamName = $routeParamName;
    }

    function currentRoute()
    {
        if ( is_null( $this->currentRoute ) )
            $this->currentRoute = $this->request->get( $this->routeParamName() );
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
        $function = $this->functorFactory->createFunctor( $route );
        if ( is_null( $function ) )
            throw new \Exception( "No function found for route \"" . $route . "\".",
                                  \Qck\Interfaces\HttpHeader::EXIT_CODE_NOT_FOUND );

        $function();
    }

    /**
     *
     * @var \Qck\Interfaces\Request
     */
    protected $request;

    /**
     *
     * @var \Qck\Interfaces\FunctorFactory
     */
    protected $functorFactory;

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

<?php

namespace Qck;

/**
 * The Router maps an Argument to a certain Function and calls it respectively.
 * 
 * @author muellerm
 */
class Router implements Interfaces\Router, Interfaces\Functor
{
    function __construct( Interfaces\AppFunctionFactory $AppFunctionFactory, Interfaces\ImmutableDict $Arguments )
    {
        $this->AppFunctionFactory = $AppFunctionFactory;
        $this->Arguments          = $Arguments;
    }

    function getCurrentRoute()
    {
        return $this->Arguments->get( $this->getRouteParamKey() );
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
        $RouteName   = $this->getCurrentRoute();
        $AppFunction = $this->AppFunctionFactory->createAppFunction( $RouteName );
        if ( is_null( $AppFunction ) )
            throw new \Exception( "No AppFunction found for Route \"" . $RouteName . "\"." );

        $AppFunction();
    }

    public function __invoke()
    {
        $this->run();
    }

    /**
     *
     * @var Interfaces\AppFunctionFactory
     */
    protected $AppFunctionFactory;

    /**
     *
     * @var Interfaces\ImmutableDict
     */
    protected $Arguments;

}

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

    function __construct( Interfaces\AppFunctionFactory $AppFunctionFactory, Interfaces\Arguments $Arguments )
    {
        $this->AppFunctionFactory = $AppFunctionFactory;
        $this->Arguments          = $Arguments;
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
        $RouteName   = $this->getCurrentRoute();
        $AppFunction = $this->AppFunctionFactory->createAppFunction( $RouteName );
        if ( is_null( $AppFunction ) )
            throw new \Exception( "No AppFunction found for Route \"" . $RouteName . "\".",
                                  Interfaces\HttpHeader::EXIT_CODE_NOT_FOUND );

        $AppFunction->run( $this );
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

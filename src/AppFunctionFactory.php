<?php

namespace Qck;

class AppFunctionFactory implements Interfaces\AppFunctionFactory
{

    function __construct( string $DefaultRoute, array $Routes )
    {
        $this->DefaultRoute = $DefaultRoute;
        $this->Routes       = $Routes;
    }

    function setDefaultRoute( string $DefaultRoute ): void
    {
        $this->DefaultRoute = $DefaultRoute;
    }

    function addRoute( $RouteName, $Fqcn )
    {
        $this->Routes[ $RouteName ] = $Fqcn;
    }

    public function createAppFunction( $Route )
    {
        if ( is_null( $Route ) )
            $Route = $this->DefaultRoute;
        if ( isset( $this->Routes[ $Route ] ) )
        {
            $Fqcn = $this->Routes[ $Route ];
            return new $Fqcn;
        }
        return null;
    }

    /**
     *
     * @var string
     */
    protected $DefaultRoute;

    /**
     *
     * @var string[]
     */
    protected $Routes;

}

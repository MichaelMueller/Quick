<?php

namespace Qck;

/**
 * The FrontController gets the currently selected controller of the application
 *
 * @author muellerm
 */
class NamespaceAppFunctionFactory implements Interfaces\AppFunctionFactory
{

    public function __construct( $DefaultRoute, array $ControllerNamespaces )
    {
        $this->DefaultRoute         = $DefaultRoute;
        $this->ControllerNamespaces = $ControllerNamespaces;
    }

    public function createAppFunction( $RouteName )
    {
        if ( is_null( $RouteName ) )
            $RouteName = $this->DefaultRoute;
        if ( !preg_match( "/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $RouteName ) )
            return null;
        foreach ( $this->ControllerNamespaces as $ControllerNamespace )
        {
            $Fqcn = $ControllerNamespace . "\\" . $RouteName;
            if ( class_exists( $Fqcn, true ) )
                return new $Fqcn();
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
    protected $ControllerNamespaces;

}

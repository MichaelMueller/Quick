<?php

namespace Qck\Interfaces;

/**
 * Interface for a Path object
 * @author muellerm
 */
interface AppFunctionFactory
{

    /**
     * @return Route or null
     */
    function createAppFunction( $RouteName );
}

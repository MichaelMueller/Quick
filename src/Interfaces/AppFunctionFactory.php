<?php

namespace Qck\Interfaces;

/**
 * Interface for a Path object
 * @author muellerm
 */
interface AppFunctionFactory
{

    /**
     * @return callable|null
     */
    function createAppFunction( $RouteName );
}

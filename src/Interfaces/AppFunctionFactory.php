<?php

namespace Qck\Interfaces;

/**
 * Interface for a Path object
 * @author muellerm
 */
interface AppFunctionFactory
{

    /**
     * @return AppFunction or null
     */
    function createAppFunction( $RouteName );
}

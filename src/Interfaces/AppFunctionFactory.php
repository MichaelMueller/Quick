<?php

namespace Qck\Interfaces;

/**
 * @author muellerm
 */
interface AppFunctionFactory
{
    
    /**
     * 
     * @return callable or null
     */
    function createAppFunction($route);
}

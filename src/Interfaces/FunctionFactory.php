<?php

namespace Qck\Interfaces;

/**
 * @author muellerm
 */
interface FunctionFactory
{
    
    /**
     * 
     * @return callable or null
     */
    function create($route);
}

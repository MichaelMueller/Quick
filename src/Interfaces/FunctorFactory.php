<?php

namespace Qck\Interfaces;

/**
 * @author muellerm
 */
interface FunctorFactory
{
    
    /**
     * 
     * @return Functor|null
     */
    function createFunctor($name);
}

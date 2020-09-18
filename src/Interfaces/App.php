<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface App extends Functor
{

    /**
     * @return string
     */
    function buildUrl( $route, array $params = [] );

    /**
     * @return string
     */
    function currentRoute();

    /**
     * @return string
     */
    function routeParamName();
    
    /**
     * @return Arguments
     */
    function arguments();
}

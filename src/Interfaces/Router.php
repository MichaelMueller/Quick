<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface Router
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
}

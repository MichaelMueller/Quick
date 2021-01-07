<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface Router
{

    /**
     * @return App
     */
    function app();
    
    /**
     * @return string
     */
    function buildUrl( $route, array $params = [] );

    /**
     * @return string
     */
    function currentRoute();

}

<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Route implements \Qck\Interfaces\Route
{

    function __construct( $routeName, $appFunctionFqcn )
    {
        $this->routeName = $routeName;
        $this->appFunctionFqcn = $appFunctionFqcn;
    }

    function getRouteName()
    {
        return $this->routeName;
    }

    function getAppFunctionFqcn()
    {
        return $this->appFunctionFqcn;
    }

    protected $routeName;
    protected $appFunctionFqcn;

}

<?php

namespace Qck\Demo\HelloWorldApp;

class AppFunctionFactory implements \Qck\Interfaces\AppFunctionFactory
{

    public function createAppFunction( $RouteName )
    {
        return new \Qck\Demo\HelloWorldApp\HelloWorldAppFunction();
    }

}

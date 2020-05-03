<?php

namespace Qck\Demo\HelloWorld;

class AppFunctionFactory implements \Qck\Interfaces\AppFunctionFactory
{

    public function createAppFunction( $RouteName )
    {
        return new \Qck\Demo\HelloWorld\HelloWorldFunction();
    }

}

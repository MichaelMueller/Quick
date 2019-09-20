<?php

namespace Qck\Demo\UserDirectoryApp;

class AppFunctionFactory implements \Qck\Interfaces\AppFunctionFactory
{

    public function createAppFunction( $RouteName )
    {
        if( $RouteName == "login")
            return new \Qck\Demo\UserDirectoryApp\AppFunctions\Login();
        else
            return new \Qck\Demo\UserDirectoryApp\AppFunctions\LoginForm();
    }

}

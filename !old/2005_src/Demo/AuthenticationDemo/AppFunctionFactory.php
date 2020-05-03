<?php

namespace Qck\Demo\AuthenticationDemo;

class AppFunctionFactory implements \Qck\Interfaces\AppFunctionFactory
{

    public function createAppFunction( $RouteName )
    {
        if ($RouteName == "login")
            return new \Qck\Demo\AuthenticationDemo\AppFunctions\Login();
        else if ($RouteName == "loggedInPage")
            return new \Qck\Demo\AuthenticationDemo\AppFunctions\LoggedInPage();
        else if ($RouteName == "logout")
            return new \Qck\Demo\AuthenticationDemo\AppFunctions\Logout();
        else
            return new \Qck\Demo\AuthenticationDemo\AppFunctions\LoginForm();
    }

}

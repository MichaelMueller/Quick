<?php

namespace Qck\Demo\AuthenticationDemo\AppFunctions;

/**
 * 
 * @author muellerm
 */
class Login implements \Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $App, \Qck\Interfaces\Arguments $Args )
    {
        /* @var $App \Qck\Demo\AuthenticationDemo\App */
        if ($App->getAuthenticator()->check( $Args->get( "Username" ), $Args->get( "Password" ) ))
        {
            $App->getHttpHeader()->sendRedirect( $App->buildUrl( "loggedInPage" ) );
        }
        else
        {
            $App->getHttpHeader()->sendRedirect( $App->buildUrl( "loginForm", ["WrongCredentials" => 1] ) );
        }
    }

}

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
            $SessionId = $App->getSession()->startSession( $Args->get( "Username" ) );
            if(!$Args->isHttpRequest())
                print "your session parameter: ";
            $App->getHttpHeader()->sendRedirect( $App->buildUrl( "loggedInPage" ) );
        }
        else
        {
            $App->getHttpHeader()->sendRedirect( $App->buildUrl( "loginForm", ["WrongCredentials" => 1] ) );
        }
    }

}

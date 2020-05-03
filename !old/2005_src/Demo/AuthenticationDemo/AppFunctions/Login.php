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
            if ($Args->isHttpRequest())
                $App->getHttpHeader()->redirect( $App->buildUrl( "loggedInPage" ) );
            else
                print "your session parameter: " . $App->getSession()->getSessionIdKey() . ": " . $SessionId;
        }
        else
        {
            if ($Args->isHttpRequest())
                $App->getHttpHeader()->redirect( $App->buildUrl( "loginForm", ["WrongCredentials" => 1] ) );
            else
                print "Wrong credentials";
        }
    }

}

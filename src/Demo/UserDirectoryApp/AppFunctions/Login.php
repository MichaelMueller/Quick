<?php

namespace Qck\Demo\UserDirectoryApp\AppFunctions;

/**
 * 
 * @author muellerm
 */
class Login implements \Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $App, \Qck\Interfaces\Arguments $Args )
    {
        /* @var $App \Qck\Demo\UserDirectoryApp\App */
        if ($App->getAuthenticator()->check( $Args->get( "Username" ), $Args->get( "Password" ) ))
        {
            $Link = $Query ? "?" . $Query : $this->buildUrl( "dashboard" );
            $this->getHttpResponder()->redirect( $Link );
        }
        else
        {
            $this->getHttpResponder()->redirect( $this->buildUrl( "loginForm" ), ["WrongCredentials" => 1] );
        }
    }

}

<?php

namespace Qck\Demo\AuthenticationDemo\AppFunctions;

/**
 * 
 * @author muellerm
 */
class Logout implements \Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $App, \Qck\Interfaces\Arguments $Args )
    {
        /* @var $App \Qck\Demo\AuthenticationDemo\App */
        $App->getSession()->stopSession();
        $App->getHttpHeader()->redirect( $App->buildUrl( "loginForm" ) );
    }

}

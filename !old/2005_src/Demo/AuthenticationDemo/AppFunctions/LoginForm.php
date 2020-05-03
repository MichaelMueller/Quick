<?php

namespace Qck\Demo\AuthenticationDemo\AppFunctions;

/**
 * 
 * @author muellerm
 */
class LoginForm implements \Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $App, \Qck\Interfaces\Arguments $Args )
    {
        /* @var $App \Qck\Demo\AuthenticationDemo\App */
        $LoginUrl = $App->buildUrl( "login" );
        $LoginForm = new \Qck\Demo\AuthenticationDemo\Snippets\LoginForm( $LoginUrl );        
        
        $HtmlPage = $App->createHtmlPage( "Login Form", $LoginForm );
        $App->getHttpHeader()->sendContent( $HtmlPage );
    }

}

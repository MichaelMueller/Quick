<?php

namespace Qck\Demo\UserDirectoryApp\AppFunctions;

/**
 * 
 * @author muellerm
 */
class LoginForm implements \Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $App, \Qck\Interfaces\Arguments $Args )
    {
        /* @var $App \Qck\Demo\UserDirectoryApp\App */
        $LoginUrl = $App->buildUrl( "login" );
        $LoginForm = new \Qck\Demo\UserDirectoryApp\Snippets\LoginForm( $LoginUrl );        
        
        $HtmlPage = $App->createHtmlPage( "Login Form", $LoginForm );
        $App->getHttpHeader()->sendContent( $HtmlPage );
    }

}

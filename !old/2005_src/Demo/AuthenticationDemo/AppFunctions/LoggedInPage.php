<?php

namespace Qck\Demo\AuthenticationDemo\AppFunctions;

/**
 * 
 * @author muellerm
 */
class LoggedInPage implements \Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $App, \Qck\Interfaces\Arguments $Args )
    {
        /* @var $App \Qck\Demo\AuthenticationDemo\App */
        $Username = $App->getSession()->getUsername();
        if (!$Username)
            throw new \Exception( "not logged in", \Qck\HttpHeader::EXIT_CODE_FORBIDDEN );
        
        $LogoutUrl = $App->buildUrl( "logout" );
        $LoggedInPage = new \Qck\Demo\AuthenticationDemo\Snippets\LoggedInPage( $LogoutUrl, $Username );

        $HtmlPage = $App->createHtmlPage( "Login Form", $LoggedInPage );
        $App->getHttpHeader()->sendContent( $HtmlPage );
    }

}

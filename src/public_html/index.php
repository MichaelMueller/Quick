<?php

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * 
 * @author muellerm
 */
class HelloWorld implements \Qck\AppFunction
{

    public function run( \Qck\App $app )
    {
        $content = sprintf( "Hello World. My name is %s. Your IP: %s", $app->name(), $app->httpRequest()->ipAddress()->value() );
        $app->httpResponse()->createContent( $content )->response()->send();
    }

}

Qck\App::create("Demo App", HelloWorld::class, true)->run();


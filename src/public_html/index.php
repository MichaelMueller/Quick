<?php

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * 
 * @author muellerm
 */
class HelloWorld implements \Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $app )
    {
        $app->httpResponse()->createContent( "Hello World. Your IP: " . $app->httpRequest()->ipAddress()->value() )->response()->send();
    }

}

Qck\App::createConfig( "Demo App", HelloWorld::class )->setShowErrors( true )->runApp();


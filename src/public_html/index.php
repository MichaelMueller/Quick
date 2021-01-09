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
        $content = sprintf( "Hello World. My name is %s. Your IP: %s", $app->name(), $app->httpRequest()->ipAddress()->value() );
        $app->httpResponse()->createContent( $content )->response()->send();
    }

}

(new \Qck\App( "Demo App", HelloWorld::class ) )->setShowErrors( true )->run();


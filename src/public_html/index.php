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
        $content = sprintf( "Hello World. My name is %s.", $app->name() );
        if ( $app->request()->isHttpRequest() )
            $content .= sprintf( "Your IP: %s", $app->request()->httpRequest()->ipAddress()->value() );
        Qck\HttpResponse::new()->createContent( $content )->response()->send();
    }

}

Qck\App::new( "Demo App", HelloWorld::class )->setShowErrors( true )->run();


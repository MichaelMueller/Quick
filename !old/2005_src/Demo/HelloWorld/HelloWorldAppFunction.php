<?php

namespace Qck\Demo\HelloWorld;

/**
 * 
 * @author muellerm
 */
class HelloWorldFunction implements \Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $App, \Qck\Interfaces\Arguments $Args )
    {
        if ($Args->isHttpRequest())
            print "<html><head><title>hello world</title></head><body><pre>";

        $message = $Args->get( "message" );
        print "hello world" . PHP_EOL;
        if ($message)
            print "your message: " . $message;

        if ($Args->isHttpRequest())
            print "</pre></body></html>";
    }

}

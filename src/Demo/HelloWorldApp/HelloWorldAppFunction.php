<?php

namespace Qck\Demo\HelloWorldApp;

/**
 * 
 * @author muellerm
 */
class HelloWorldAppFunction implements \Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $App, \Qck\Interfaces\Arguments $Args )
    {
        if ($Args->isHttpRequest())
            print "<html><head><title>hello world</title></head><body>";

        print "hello world";

        if ($Args->isHttpRequest())
            print "</body></html>";
    }

}

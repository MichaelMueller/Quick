<?php

namespace Qck\DemoAppFunctions;

/**
 * 
 * @author muellerm
 */
class HelloWorld implements \Qck\Interfaces\AppFunction
{

    public function run(\Qck\Interfaces\App $app)
    {
        $app->httpResponse()->createContent("Hello World")->response()->send();
    }

}

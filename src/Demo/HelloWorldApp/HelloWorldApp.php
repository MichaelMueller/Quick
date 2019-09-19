<?php

namespace Qck\Demo\HelloWorldApp;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class HelloWorldApp extends \Qck\App
{

    function __construct( \Qck\Interfaces\Arguments $Arguments, $ShowErrors = false )
    {
        parent::__construct( new \Qck\Route( "helloWorld", HelloWorldAppFunction::class ), $Arguments, $ShowErrors );
    }

}

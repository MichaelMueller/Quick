<?php

namespace Qck\Demo\UserManagementApp;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class UserManagementApp extends \Qck\App
{

    function __construct( \Qck\Interfaces\Arguments $Arguments, $ShowErrors = false )
    {
        parent::__construct( new \Qck\Route( "helloWorld", UserManagementAppFunction::class ), $Arguments, $ShowErrors );
    }

}

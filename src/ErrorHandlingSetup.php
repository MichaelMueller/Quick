<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class ErrorHandlingSetup
{

    function setup($DevMode, $Cli)
    {
        error_reporting(E_ALL);
        ini_set('log_errors', intval(!$DevMode));
        ini_set('display_errors', intval($DevMode));
        ini_set('html_errors', intval(!$Cli));
    }

}

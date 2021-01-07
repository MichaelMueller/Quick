<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface AppFunction
{

    /**
     * @return string
     */
    function name();
    
    /**
     * @return array
     */
    function args();
    
    /**
     * @return User
     */
    function admin();

    /**
     * @return Exception
     */
    function exception();

    /**
     * @return Router
     */
    function router();

    /**
     * @return Env
     */
    function env();

    /**
     * @return HttpRequest|null or null if run from the command line
     */
    function httpRequest();
    
    /**
     * @return Mailer|null or null
     */
    function mailer();
}

<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface App
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
     * @return HttpRequest|null or null if run from the command line
     */
    function httpRequest();

    /**
     * @return Router
     */
    function router();

    /**
     * @return HttpResponse|null or null if run from the command line
     */
    function httpResponse();

    /**
     * @return Exception
     */
    function createException();

    /**
     * runs a command 
     * @param string $command the actual executable
     * @return Cmd
     */
    function createCmd( $executable );
}

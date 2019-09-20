<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface Session
{

    /**
     * start session and set a username if set
     * @return string or null if none is set
     */
    function startSession( $Username );

    /**
     * @return string or null
     */
    function getUsername();

    /**
     * completely clear session
     */
    function stopSession();
}

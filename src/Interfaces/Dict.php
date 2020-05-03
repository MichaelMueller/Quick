<?php

namespace Qck\Interfaces;

/**
 * Service class for sending messages to an admin
 * @author muellerm
 */
interface Dict extends ImmutableDict
{

    /**
     * 
     * @param mixed $Key
     * @param mixed $Value
     */
    function set( $Key, $Value );
}

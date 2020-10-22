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
     * @param array $data
     * @return Dict
     */
    function fromArray( array $data );

    /**
     * 
     * @param array $data
     * @return Dict
     */
    function merge( ...$arrays );

    /**
     * 
     * @param mixed $key
     * @param mixed $value
     * @return Dict
     */
    function set( $key, $value );

    /**
     * 
     * @param string $key
     * @return Dict
     */
    function remove( ...$keys );

    /**
     * @return Dict
     */
    function clear();
}

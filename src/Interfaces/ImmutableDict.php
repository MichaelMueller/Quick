<?php

namespace Qck\Interfaces;

/**
 * Service class for sending messages to an admin
 * @author muellerm
 */
interface ImmutableDict
{

    /**
     * 
     * @param mixed $key
     * @return mixed A value or null
     */
    function get( $key );

    /**
     * @return bool
     */
    function has( $key );

    /**
     * @return string
     */
    function implode( $glueKeyValue = ": ", $glueRecord = ", " );

    /**
     * @return array
     */
    function toArray( $ignoreKeys = [] );
}

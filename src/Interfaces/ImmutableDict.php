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
     * @param mixed $Key
     * @return mixed A value or null
     */
    function get( $Key );

    /**
     * @return bool
     */
    function has( $Key );
    
    /**
     * @return string
     */
    function implodeWithKeys($glueKeyValue=": ", $glueRecord=", ");
    
    /**
     * @return array
     */
    function toArray();
}

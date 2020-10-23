<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Record
{

    /**
     * 
     * @param string $key
     * @param scalar $value
     * @return Record
     */
    function set( $key, scalar $value );

    /**
     * @return array
     */
    function toArray();
}

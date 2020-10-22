<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Storage extends 
{

    /**
     * Persist changes
     */
    function commit();

    /**
     * 
     * @param mixed $id
     * @return array
     */
    function get( $id );

    /**
     * 
     * @param mixed $id
     * @return scalar an id
     */
    function set( array $record, $id=null );
    
}

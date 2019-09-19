<?php

namespace Qck\Interfaces;

/**
 * Service class for sending messages to an admin
 * @author muellerm
 */
interface ArrayStorage
{

    /**
     * 
     * @param mixed $Key
     * @param mixed $Value
     */
    function save( array $Data, $Id = null, $Fqcn = null );

    /**
     * 
     * @param mixed $Key
     * @param mixed $Value
     */
    function load( $Id, $Fqcn = null );
}

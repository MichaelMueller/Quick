<?php

namespace Qck\Interfaces;

/**
 * A central interface for creating objects
 * @author muellerm
 */
interface ObjectFactory
{

    /**
     * creates a new object
     * 
     * @param string $Fqcn
     * @param array $Data
     */
    function create( $Fqcn, array $Args = [] );

    /**
     * creates a new object
     * 
     * @param string $Fqcn
     * @param array $Data
     */
    function createArgs( $Fqcn, ...$Args );
}

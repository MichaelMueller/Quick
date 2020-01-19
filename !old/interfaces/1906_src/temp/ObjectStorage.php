<?php

namespace Qck\Interfaces;

/**
 * Storage for Object (Object Graphs)
 * 
 * @author muellerm
 */
interface ObjectStorage
{

    /**
     * Save an Object (respectively the ENTIRE OBJECT GRAPH) to somewhere
     * 
     * @param mixed $Object
     * @return void
     */
    function save( $Object );
}

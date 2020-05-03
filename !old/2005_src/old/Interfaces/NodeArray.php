<?php

namespace Qck\Interfaces;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface NodeArray extends Node
{

    /**
     * 
     * @param \Qck\Interfaces\Node $Node
     */
    function add( Node $Node );

    /**
     * 
     * @param int $Idx
     */
    function remove( $Idx );
    
    /**
     * 
     * @return int
     */
    function size();

    /**
     * @param int $Idx
     * @return Node a Node object
     */
    function at( $Idx );
}

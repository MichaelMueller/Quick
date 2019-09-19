<?php

namespace Qck\Interfaces;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface DataNode extends Node
{

    /**
     * @param string $Key
     * @return void
     */
    function set( $Key, $Value );
 
    /**
     * @param string $Key
     * @return mixed
     */
    function get( $Key );

    /**
     * @param string $Key
     * @return bool
     */
    function has( $Key );

    /**
     * 
     * @return string[]
     */
    function keys();

    /**
     * @param string $Key
     * @return void
     */
    function remove( $Key );
}

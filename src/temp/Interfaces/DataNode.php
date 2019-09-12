<?php

namespace Qck\Interfaces;

/**
 * Service class for sending messages to an admin
 * @author muellerm
 */
interface DataNode
{

    /**
     * 
     * @param mixed $Key
     * @param mixed $Value
     */
    function setId( $Id );

    /**
     * 
     * @param mixed $Value
     */
    function add( $Value );

    /**
     * 
     * @param mixed $Key
     * @param mixed $Value
     */
    function set( $Key, $Value );

    /**
     * @param mixed $Key
     */
    function remove( $Key );

    /**
     * 
     * @param mixed $Key
     * @param mixed $Default
     * @return callable A function for creating a default or $Default
     */
    function get( $Key, callable $Default = null );

    /**
     * @return mixed An Id for this record or null if no Id provided yet!
     */
    function getId();

    /**
     * @return mixed An Id for this record or null if no Id provided yet!
     */
    function getData();

    /**
     * @return array an array of keys or empty array
     */
    function keys();

    /**
     * @return bool
     */
    function has( $Key );

    /**
     * @return DataNode a new DataNode
     */
    function create( $Id = null );

    /**
     * saves this active record (and potentially contained active records)
     */
    function save();
}

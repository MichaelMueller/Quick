<?php

namespace Qck\Interfaces;

/**
 * An interface for conversion of objects to an array of scalar. Implementing classes must "know" these
 * classes.
 *  
 * @author muellerm
 */
interface DataObject
{

    /**
     * 
     * @param string $Id
     */
    function setId( $Id );

    /**
     * 
     * @return string
     */
    function getId();

    /**
     * 
     * @return string
     */
    function getFqcn();
    
    /**
     * 
     * @return array of scalars!
     */
    function toArray();

    /**
     * 
     * @param array $Array
     */
    function fromArray( array $Array );
}

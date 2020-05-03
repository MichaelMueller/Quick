<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface ArraySerializer
{

    /**
     * 
     * @param array $Array
     * @throws \InvalidArgumentException if $Array contains other values than scalar values
     */
    function serialize( array $Array );

    /**
     * @param string $DataString
     * @return array 
     */
    function unserialize( $DataString );

    /**
     * 
     * @return string an extension for the file to be written WITHOUT DOT!
     */
    function getFileExtension();
}

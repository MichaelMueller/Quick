<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface LockFile
{

    /**
     * @return string
     */
    function read();

    /**
     * @return resource
     */
    function writeHandle($endOfFile=false);

    /**
     * @return resource
     */
    function write( $contents );
    
    /**
     * @return resource
     */
    function append( $contents );

    /**
     * @return void
     */
    function close();
}

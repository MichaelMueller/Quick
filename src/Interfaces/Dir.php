<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Dir
{

    /**
     * 
     * @param bool $AssertCreated
     * @return string
     */
    function get( $AssertCreated = true );

    /**
     * 
     * @param string $SubDir
     * @param bool $AssertCreated
     * @return string
     */
    function getSubDir( $SubDir, $AssertCreated = true );

    /**
     * 
     * @param string $RelativeFilePath
     * @param bool $AssertCreated
     * @return string
     */
    function getFilePath( $RelativeFilePath, $AssertCreated = true );
}

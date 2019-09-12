<?php

namespace Qck\Interfaces;

/**
 *  
 * @author muellerm
 */
interface DirectoryConfig
{

    /**
     * 
     * @return string
     */
    function getProjectDir( $SubPath = null );

    /**
     * 
     * @return string
     */
    function getDataDir( $SubPath = null, $IsFile = false, $Create = false );

    /**
     * 
     * @return string
     */
    function getTmpDir( $SubPath = null, $IsFile = false, $Create = false );
}

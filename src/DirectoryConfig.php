<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class DirectoryConfig implements Interfaces\DirectoryConfig
{

    function __construct( $ProjectDir )
    {
        $this->ProjectDir = $ProjectDir;
    }

    function getProjectDir( $SubPath = null )
    {
        return $this->ProjectDir . $SubPath;
    }

    function getAssetsDir( $SubPath = null )
    {
        return $this->ProjectDir . "/assets" . $SubPath;
    }

    function getDataDir( $SubPath = null, $IsFile = false, $Create = false )
    {
        return $this->createIfNotExists( $this->ProjectDir . "/var/data" . $SubPath, $Create, $IsFile );
    }

    function getTmpDir( $SubPath = null, $IsFile = false, $Create = false )
    {
        return $this->createIfNotExists( $this->ProjectDir . "/var/tmp" . $SubPath, $Create, $IsFile );
    }

    function createIfNotExists( $FilePath, $createIfNotExists, $IsDir )
    {
        if ($createIfNotExists && !file_exists( $FilePath ))
        {
            if ($IsDir)
                mkdir( $FilePath, 0777, true );
            else
            {
                $ParentDir = dirname( $FilePath );
                if (!is_dir( $ParentDir ))
                    mkdir( $ParentDir, 0777, true );
                touch( $FilePath );
            }
        }
        return $FilePath;
    }

    protected $ProjectDir;

}

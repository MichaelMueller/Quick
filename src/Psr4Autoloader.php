<?php

namespace Mbits\Labeling;

/**
 * Description of Psr4Autoloader
 *
 * @author muellerm
 */
class Psr4Autoloader
{

    function __construct( array $prefixesToPaths=[] )
    {
        foreach ( $prefixesToPaths as $prefix => $paths )
            foreach ( $paths as $path )
                $this->add( $prefix, $path );
        $this->register();
    }

    function add( $prefix, $path )
    {
        if ( isset( $this->prefixesToPaths[ $prefix ] ) )
            $this->prefixesToPaths[ $prefix ][] = $path;
        else
        {
            $this->prefixesToPaths[ $prefix ] = array ( $path );
        }
        $this->sort = true;
    }

    function register()
    {
        spl_autoload_register( array ( $this, "autoload" ) );
    }

    function autoload( $class )
    {
        if ( $this->sort )
        {
            krSort( $this->prefixesToPaths );
            $this->sort = false;
        }
        foreach ( $this->prefixesToPaths as $prefix => $paths )
        {
            // does the class use the namespace prefix?
            $len = strlen( $prefix );
            if ( strncmp( $prefix, $class, $len ) !== 0 )
            {
                // no, move to the next registered autoloader
                continue;
            }

            // correct prefix now find file
            $file = null;
            foreach ( $paths as $base_dir )
            {
                // get the relative class name
                $relative_class = substr( $class, $len );

                // replace the namespace prefix with the base directory, replace namespace
                // separators with directory separators in the relative class name, append
                // with .php
                $file = $base_dir . DIRECTORY_SEPARATOR . str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';

                // if the file exists, require it
                if ( file_exists( $file ) )
                    break;
            }
            if ( $file && file_exists( $file ) )
            {
                require_once $file;
                break;
            }
        }
    }

    /**
     *
     * @var array 
     */
    protected $prefixesToPaths = array ();

    /**
     *
     * @var bool
     */
    protected $sort = false;

}

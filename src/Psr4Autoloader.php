<?php

namespace Qck;

/**
 * Description of Psr4Autoloader
 *
 * @author muellerm
 */
class Psr4Autoloader
{

    function __construct()
    {
        $this->add( "Qck\\", __DIR__ );
    }

    function add( $prefix, $path )
    {
        if ( isset( $this->PrefixToPath[ $prefix ] ) )
            $this->PrefixToPath[ $prefix ][] = $path;
        else
        {
            $this->PrefixToPath[ $prefix ] = array ( $path );
        }
        $this->Sort = true;
    }

    function register()
    {
        spl_autoload_register( array ( $this, "autoload" ) );
    }

    function autoload( $class )
    {
        if ( $this->Sort )
        {
            krSort( $this->PrefixToPath );
            $this->Sort = false;
        }
        foreach ( $this->PrefixToPath as $prefix => $paths )
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
    protected $PrefixToPath = array ();

    /**
     *
     * @var bool
     */
    protected $Sort = false;

}

<?php

namespace Qck;

/**
 * App class is essentially the class to start.
 * It is the basic error handler and autoloading functionality.
 * No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class BasicSetup
{

    function __construct( bool $showErrors )
    {
        $this->showErrors = $showErrors;
        $this->initialize();
    }

    function initialize()
    {
        if ( !$this->booted )
        {
            // ERROR HANDLING
            error_reporting( E_ALL );
            ini_set( 'log_errors', intval( $this->ShowErrors ) );
            ini_set( 'display_errors', intval( $this->ShowErrors ) );
            ini_set( 'html_errors', intval( $this->isHttpRequest() ) );
            // AUTOLOAD
            set_error_handler( array ( $this, "error_to_exception" ) );
            set_exception_handler( array ( $this, "exception_handler" ) );
            $this->addAutoloadPath( "Qck\\", __DIR__ );
            spl_autoload_register( array ( $this, "autoload" ) );

            $this->booted = true;
        }
    }

    function addAutoloadPath( $prefix, $path )
    {
        if ( isset( $this->prefixesToPath[ $prefix ] ) )
            $this->prefixesToPath[ $prefix ][] = $path;
        else
            $this->prefixesToPath[ $prefix ]   = array ( $path );
    }

    function error_to_exception( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exception_handler( $exception )
    {
        /* @var $Exception \Exception */
        if ( $this->isHttpRequest() )
            http_response_code( 500 );

        throw $exception;
    }

    function autoload( $class )
    {
        foreach ( $this->prefixesToPath as $prefix => $paths )
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

    public function isHttpRequest()
    {
        if ( is_null( $this->isHttpRequest ) )
            $this->isHttpRequest = !isset( $_SERVER[ "argv" ] ) || is_null( $_SERVER[ "argv" ] ) || is_string( $_SERVER[ "argv" ] );
        return $this->isHttpRequest;
    }

    /**
     *
     * @var bool
     */
    protected $showErrors = false;

    /**
     *
     * @var array 
     */
    protected $prefixesToPath = array ();

    // State

    /**
     *
     * @var bool
     */
    protected $booted = false;

    /**
     *
     * @var mixed
     */
    protected $isHttpRequest;

}

<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Arguments implements Interfaces\Arguments
{

    function __construct( array $data = [] )
    {
        $this->createData( $data );
    }
    
    function set($Key, $Value)
    {
        $this->Data[$Key] = $Value;
     }

    function getData()
    {
        return $this->Data;
    }

    public function keys()
    {
        return array_keys( $this->Data );
    }

    public function get( $Name, $Default = null )
    {
        return isset( $this->Data[ $Name ] ) ? $this->Data[ $Name ] : $Default;
    }

    public function has( $Name )
    {
        return isset( $this->Data[ $Name ] );
    }

    public function isHttpRequest()
    {
        static $HttpRequest = null;
        if ( is_null( $HttpRequest ) )
            $HttpRequest        = !isset( $_SERVER[ "argv" ] ) || is_null( $_SERVER[ "argv" ] ) || is_string( $_SERVER[ "argv" ] );
        return $HttpRequest;
    }

    protected function createData( array $data = [] )
    {
        if ( $this->isHttpRequest() )
            $this->Data = $_REQUEST;
        else
            $this->Data = $this->parseArgv( $_SERVER[ "argv" ] );

        $this->Data = array_merge( $this->Data, $data );
    }

    protected function parseArgv( array $argv )
    {
        $Data = [];
        if ( count( $argv ) > 1 )
            return parse_str( $argv[ 1 ], $Data );
        return $Data;
    }

    /**
     *
     * @var array
     */
    protected $Data = [];

}

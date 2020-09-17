<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Arguments implements \Qck\Interfaces\Arguments
{

    function __construct( array $userArgs = [] )
    {
        $this->createArgs( $userArgs );
    }

    function set( $Key, $Value )
    {
        $this->args[ $Key ] = $Value;
    }

    function toArray()
    {
        return $this->args;
    }

    public function keys()
    {
        return array_keys( $this->args );
    }

    public function get( $Name, $Default = null )
    {
        return isset( $this->args[ $Name ] ) ? $this->args[ $Name ] : $Default;
    }

    public function has( $Name )
    {
        return isset( $this->args[ $Name ] );
    }

    public function valid()
    {
        if ( is_null( $this->httpRequest ) )
            $this->httpRequest = !isset( $_SERVER[ "argv" ] ) || is_null( $_SERVER[ "argv" ] ) || is_string( $_SERVER[ "argv" ] );
        return $this->httpRequest;
    }

    protected function createArgs( array $userArgs = [] )
    {
        if ( $this->httpRequest() )
            $this->args = array_merge( $_COOKIE, $_GET, $_POST );
        else
            $this->args = $this->parseArgv( $_SERVER[ "argv" ] );

        $this->args = array_merge( $this->args, $userArgs );
    }

    protected function parseArgv( array $argv )
    {
        if ( $this->cliParser )
            return $this->cliParser->parse( $argv );
        else if ( count( $argv ) > 1 )
            return parse_str( $argv[ 1 ], $args );
        else
            return [];
    }

    /**
     *
     * @var array
     */
    protected $args = [];

    /**
     *
     * @var \Qck\Interfaces\CliParser
     */
    protected $cliParser;

    // STATE

    /**
     *
     * @var bool
     */
    protected $httpRequest;

}

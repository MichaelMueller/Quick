<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Arguments implements Interfaces\Dict
{

    function __construct( Interfaces\Dict $userArgs )
    {
        $this->args      = $userArgs;
        $this->cliParser = $cliParser;
    }

    function get()
    {
        if ( $this->isHttp() )
            $args = array_merge( $_COOKIE, $_GET, $_POST );
        else
            $args = $this->parseArgv( $_SERVER[ "argv" ] );

        return array_merge( $args, $this->args );
    }

    function fillDict( Interfaces\Dict $dict )
    {
        $dict->fromArray( $this->get() );
        return $dict;
    }

    protected function parseArgv( array $argv )
    {
        if ( $this->cliParser )
            return $this->cliParser->parse( $argv );
        else if ( count( $argv ) > 1 )
            return parse_str( $argv[ 1 ], $argv );
        else
            return [];
    }

    /**
     *
     * @var array
     */
    protected $args;

    /**
     *
     * @var \Qck\Interfaces\CliParser
     */
    protected $cliParser;

}

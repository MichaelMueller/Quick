<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Request implements \Qck\Interfaces\Request
{

    function __construct( array $args )
    {
        $this->args = $args;
    }

    public function isHttp()
    {
        if ( is_null( $this->http ) )
            $this->http = !isset( $_SERVER[ "argv" ] ) || is_null( $_SERVER[ "argv" ] ) || is_string( $_SERVER[ "argv" ] );
        return $this->http;
    }

    public function get( $key, $default = null )
    {
        $this->mergeArgs();
        return isset( $this->args[ $key ] ) ? $this->args[ $key ] : $default;
    }

    protected function mergeArgs()
    {
        if ( !$this->argsMerged )
        {
            if ( $this->isHttp() )
                $this->args = array_merge( $_COOKIE, $_GET, $_POST, $this->args );
            else
                $this->args = array_merge( $this->cliParser->parse( $_SERVER[ "argv" ] ), $this->args );

            $this->argsMerged = true;
        }
    }

    /**
     *
     * @var array
     */
    protected $args;

    /**
     *
     * @var Interfaces\CliParser
     */
    protected $cliParser;

    // STATE

    /**
     *
     * @var bool
     */
    protected $http;

    /**
     *
     * @var bool
     */
    protected $argsMerged;

}

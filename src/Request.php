<?php

namespace Qck;

/**
 * 
 */
class Request
{

    function __construct( array $userArgs = [] )
    {
        $this->userArgs = $userArgs;
    }

    function isHttpRequest()
    {
        return false;
    }

    function has( $key )
    {
        $this->assertArgs();
        return isset( $this->args[ $key ] );
    }

    function get( $key, $default = null )
    {
        $this->assertArgs();
        return $this->args[ $key ] ?? $default;
    }

    public function args()
    {
        $this->assertArgs();
        return $this->args;
    }

    protected function assertArgs()
    {

        if ( is_null( $this->args ) )
        {
            // create args
            if ( $this->isHttpRequest() )
                $this->args = array_merge( $_COOKIE, $_GET, $_POST, $this->userArgs );
            else
            {
                $cmdArgs = count( $_SERVER[ "argv" ] ) > 1 ? parse_str( $_SERVER[ "argv" ][ 1 ] ) : [];
                $this->args = array_merge( $cmdArgs, $this->userArgs );
            }
        }
        return $this->args;
    }

    /**
     *
     * @var array
     */
    protected $userArgs = [];

    /**
     *
     * @var array
     */
    protected $args;

}

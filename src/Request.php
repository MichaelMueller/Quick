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

    /**
     * User supplied args take precedence over the args of the environment.
     * Already parsed args are discarded and rebuilt on the next access.
     *
     * @param array $userArgs
     * @return Request
     */
    function setUserArgs( array $userArgs ): Request
    {
        $this->userArgs = $userArgs;
        $this->args     = null;
        return $this;
    }

    /**
     * 
     * @return HttpRequest or null
     */
    function httpRequest()
    {
        return null;
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

    /**
     * 
     * @return array
     */
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
                $cmdArgs = [];
                if ( isset( $_SERVER[ "argv" ] ) && count( $_SERVER[ "argv" ] ) > 1 )
                    parse_str( $_SERVER[ "argv" ][ 1 ], $cmdArgs );
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

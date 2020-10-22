<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Request implements \Qck\Interfaces\Request
{

    function __construct( Interfaces\App $app )
    {
        $this->app = $app;
    }

    public function isHttp()
    {
        if ( is_null( $this->http ) )
            $this->http = !isset( $_SERVER[ "argv" ] ) || is_null( $_SERVER[ "argv" ] ) || is_string( $_SERVER[ "argv" ] );
        return $this->http;
    }

    public function args()
    {
        if ( !$this->argsMerged )
        {
            if ( $this->isHttp() )
                $this->app->args()->merge( $_COOKIE, $_GET, $_POST, $this->app->args()->toArray() );
            else
                $this->app->args()->merge( $this->app->cliParser()->parse( $_SERVER[ "argv" ] ), $this->app->args()->toArray() );

            $this->argsMerged = true;
        }

        return $this->app->args();
    }

    /**
     *
     * @var Interfaces\App
     */
    protected $app;


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

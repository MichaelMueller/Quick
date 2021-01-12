<?php

namespace Qck;

/**
 * 
 */
class RequestFactory
{

    function request()
    {
        if ( is_null( $this->request ) )
        {
            if ( ! isset( $_SERVER[ "argv" ] ) || is_null( $_SERVER[ "argv" ] ) || is_string( $_SERVER[ "argv" ] ) )
                $this->request = new HttpRequest();
            else
                $this->request = new Request ( );
        }

        return $this->request;
    }

    /**
     *
     * @var Request
     */
    protected $request;

}

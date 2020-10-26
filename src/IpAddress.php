<?php

namespace Qck;

/**
 * IpAddress from https://raw.githubusercontent.com/zendframework/zend-http/master/src/PhpEnvironment/RemoteAddress.php
 *
 * @author muellerm
 */
class IpAddress implements \Qck\Interfaces\IpAddress
{

    function __construct( $validationFlags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE, $ip = null )
    {
        $this->validationFlags = $validationFlags;
        $this->ip              = $ip;
    }

    public function get()
    {
        if ( !$this->ip )
        {
            if ( !empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) )
            {
                //ip from share internet
                $this->ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
            }
            elseif ( !empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) )
            {
                //ip pass from proxy
                $this->ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
            }
            elseif ( !empty( $_SERVER[ 'REMOTE_ADDR' ] ) )
                $this->ip = $_SERVER[ 'REMOTE_ADDR' ];
            else
            {
                $this->ip = null;
            }
        }
        if ( $this->validationFlags )
        {
            $this->ip = filter_var( $this->ip, FILTER_VALIDATE_IP, $this->validationFlags );
            if ( $this->ip === false )
                $this->ip = null;
        }

        return $this->ip;
    }

    protected $validationFlags;
    // STATE
    protected $ip;

}

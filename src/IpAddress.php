<?php

namespace Qck;

/**
 * IpAddress from https://raw.githubusercontent.com/zendframework/zend-http/master/src/PhpEnvironment/RemoteAddress.php
 *
 * @author muellerm
 */
class IpAddress implements Interfaces\IpAddress
{

    function setValidationFlags( $ValidationFlags )
    {
        $this->ValidationFlags = $ValidationFlags;
    }

    public function get( $Validate = true )
    {
        static $ip = false;

        if ( $ip === false )
        {
            if ( !empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) )
            {
                //ip from share internet
                $ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
            }
            elseif ( !empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) )
            {
                //ip pass from proxy
                $ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
            }
            elseif ( !empty( $_SERVER[ 'REMOTE_ADDR' ] ) )
                $ip = $_SERVER[ 'REMOTE_ADDR' ];
            else
            {
                $ip = null;
            }
        }
        if ( $Validate )
        {
            if ( filter_var( $ip, FILTER_VALIDATE_IP, $this->ValidationFlags ) )
                return $ip;
            else
                return null;
        }

        return $ip;
    }

    protected $ValidationFlags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;

}

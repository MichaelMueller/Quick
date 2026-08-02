<?php

namespace Qck;

/**
 * 
 */
class IpAddress
{

    public function value()
    {
        if ( ! $this->ip )
        {
            if ( ! empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) )
            {
                //ip from share internet
                $this->ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
            }
            elseif ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) )
            {
                //ip pass from proxy
                $this->ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
            }
            elseif ( ! empty( $_SERVER[ 'REMOTE_ADDR' ] ) )
                $this->ip = $_SERVER[ 'REMOTE_ADDR' ];
            else
            {
                $this->ip = null;
            }
        }
        if ( $this->validationFlags )
        {
            $validatedIp = is_null( $this->ip ) ? false : filter_var( $this->ip, FILTER_VALIDATE_IP, $this->validationFlags );
            if ( $validatedIp === false )
                Exception::new()->error( "Invalid ip '%s'", strval( $this->ip ) )->throw();
            $this->ip = $validatedIp;
        }

        return $this->ip;
    }

    public function __toString()
    {
        return strval( $this->value() );
    }

    public function setValidationFlags( $validationFlags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE )
    {
        $this->validationFlags = $validationFlags;
        $this->ip = null;
        return $this;
    }

    /**
     *
     * @var int
     */
    protected $validationFlags;

    /**
     *
     * @var string
     */
    private $ip;

}

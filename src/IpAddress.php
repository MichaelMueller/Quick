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
            $this->ip = filter_var( $this->ip, FILTER_VALIDATE_IP, $this->validationFlags );
            if ( $this->ip === false )
                Exception::new()->error( "Invalid ip %s", $this->ip )->exception()->throw();
        }

        return $this->ip;
    }

    public function __toString()
    {
        return $this->value();
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

<?php

namespace Qck;

/**
 * A basic representing a HttpRequest
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class HttpRequest extends Request
{

    public function __construct( array $userArgs = [] )
    {
        parent::__construct( $userArgs );
    }

    function httpRequest()
    {
        return $this;
    }

    public function isHttpRequest()
    {
        return true;
    }

    public function ipAddress()
    {
        if ( is_null( $this->ipAddress ) )
            $this->ipAddress = new IpAddress();
        return $this->ipAddress;
    }

    /**
     *
     * @var \Qck\IpAddress
     */
    protected $ipAddress;

}

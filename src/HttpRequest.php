<?php

namespace Qck;

/**
 * 
 */
class HttpRequest
{

    function __construct(App $app)
    {
        $this->app = $app;
    }

    public function app()
    {
        return $this->app;
    }

    public function ipAddress()
    {
        if (is_null($this->ipAddress))
            $this->ipAddress = new IpAddress($this);
        return $this->ipAddress;
    }

    /**
     *
     * @var App
     */
    protected $app;

    /**
     *
     * @var \Qck\IpAddress
     */
    protected $ipAddress;

}

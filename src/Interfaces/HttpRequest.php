<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface HttpRequest
{

    /**
     * @return IpAddress
     */
    function ipAddress();

    /**
     * @return App
     */
    function app();
}

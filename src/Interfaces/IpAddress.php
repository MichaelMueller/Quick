<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface IpAddress
{

    /**
     * 
     * @param int $validationFlags
     * @return IpAddress $this
     */
    function setValidationFlags($validationFlags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);

    /**
     * @return string an IPv4, IPv6 address or false if no valid ip could be found
     */
    function value();
    
    /**
     * @return string same as value()
     */
    function __toString();

    /**
     * @return HttpRequest
     */
    function httpRequest();
}

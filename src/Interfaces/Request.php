<?php

namespace Qck\Interfaces;

/**
 * Encapsulation of everything that is known when a request is sent to this system
 * @author muellerm
 */
interface Request
{

    /**
     * @return bool
     */
    function isHttp();

    /**
     * @return Dict arguments
     */
    function args();
}

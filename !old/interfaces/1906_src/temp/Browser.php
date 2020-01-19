<?php

namespace Qck\Interfaces;

/**
 * Client specific information goes here.
 * @author muellerm
 */
interface Browser
{

    /**
     * @return string
     */
    function getSignature();

    /**
     * @return bool
     */
    function isKnownBrowser();
}

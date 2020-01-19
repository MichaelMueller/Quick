<?php

namespace Qck\Interfaces;

/**
 * An Config Service Class
 * 
 * @author muellerm
 */
interface AppConfig
{

    /**
     * @return Arguments
     */
    function getInputs();

    /**
     * @return CliDetector
     */
    function getCliDetector();
}

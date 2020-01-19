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
     * @return Inputs
     */
    function getInputs();

    /**
     * @return CliDetector
     */
    function getCliDetector();
}

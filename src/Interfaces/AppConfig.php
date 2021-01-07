<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface AppConfig
{

    /**
     * @return AppConfig
     */
    function setUserArgs(array $args = []);

    /**
     * @return AppConfig
     */
    function setShowErrors($showErrors = false);

    /**
     * @return AppConfig
     */
    function setDefaultRoute($defaultRoute = "Start");

    /**
     * void
     */
    function runApp();
}

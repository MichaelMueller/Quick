<?php

namespace Qck\Interfaces;

/**
 * An Config Service Class
 * 
 * @author muellerm
 */
interface AppFunction
{
    /**
     * 
     * @param \Qck\Interfaces\App $App
     * @param \Qck\Interfaces\Arguments $Args
     * @return void
     */
    function run( App $App, Arguments $Args );
}

<?php

namespace Qck\Interfaces;

/**
 * An Config Service Class
 * 
 * @author muellerm
 */
interface Controller
{

    /**
     * 
     * @param \Qck\Interfaces\App $App
     * @return void
     */
    function run( Arguments $Args, Config $Config );
}

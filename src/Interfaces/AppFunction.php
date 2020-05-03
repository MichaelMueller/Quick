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
     * @return void
     */
    function run( App $App );
}

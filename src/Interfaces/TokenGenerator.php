<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface TokenGenerator
{

    /**
     * @return string 
     */
    function create( $Length = 32 );
}

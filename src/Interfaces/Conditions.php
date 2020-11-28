<?php

namespace Qck\Interfaces;

/**
 * Client specific information goes here.
 * @author muellerm
 */
interface Conditions
{

    /**
     * 
     * @param array $array
     * @return bool
     */
    function areMetBy( $array );
}

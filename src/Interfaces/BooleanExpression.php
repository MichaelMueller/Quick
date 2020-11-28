<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface BooleanExpression
{

    /**
     * 
     * @param array $array
     * @return bool
     */
    function eval( $array );
}

<?php

namespace Qck\Interfaces;

/**
 * represents constraint(s) of a variable
 */
interface Expression
{

    /**
     * 
     * @param array $array
     * @return mixed
     */
    function eval( array $data );

}

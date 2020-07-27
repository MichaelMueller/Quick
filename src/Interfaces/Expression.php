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
    function eval( $data, array &$filteredData=[], array &$errors=[] );
    

    /**
     * @return string
     */
    function __toString();
}

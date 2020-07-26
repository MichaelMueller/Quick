<?php

namespace Qck\Interfaces;

/**
 * represents constraint(s) of a variable
 */
interface Constraint
{

    /**
     * 
     * @param array $array
     * @return array with [result, filteredData, errors)
     */
    function eval( array $data );

    // functions

    /**
     * apply length function
     * @return Constraint
     */
    function length();

    // operators

    /**
     * 
     * @return Constraint
     */
    function matches();

    /**
     * 
     * @return Constraint
     */
    function eq();

    /**
     * 
     * @return Constraint
     */
    function neq();

    /**
     * 
     * @return Constraint
     */
    function val( $value );

    /**
     * 
     * @return Constraint
     */
    function var( $varName );

    /**
     * 
     * @return Constraint new constraint sibling
     */
    function and( $varName = null, $error = null );

    /**
     * 
     * @return Constraint new constraint sibling
     */
    function or( $varName = null, $error = null );

    /**
     * 
     * @return Constraint new constraint child
     */
    function child( $varName = null, $error = null );

    /**
     * 
     * @return Constraint|null the parent or null if we are at the root
     */
    function parent();
}

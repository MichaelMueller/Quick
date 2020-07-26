<?php

namespace Qck\Interfaces;

/**
 * represents constraint(s) of a variable
 */
interface Comparisons extends BoolExpression
{

    /**
     * 
     * @return Comparisons
     */
    function and();

    /**
     * 
     * @return Comparisons
     */
    function or();

    /**
     * 
     * @return Comparisons
     */
    function not();

    /**
     * 
     * @param string $varName
     * @param string|int $error a user defined error
     * @return Compare
     */
    function compare( $varName, $error = null );

    /**
     * 
     * @return Comparisons
     */
    function parantheses( $error = null );

    /**
     * 
     * @return Comparisons|null the parent or null if we are at the root
     */
    function close();
}

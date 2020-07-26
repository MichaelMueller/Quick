<?php

namespace Qck\Interfaces;

/**
 * represents constraint(s) of a variable
 */
interface Comparison extends BoolExpression
{
    /**
     * 
     * @return Comparison
     */
    function length();

    /**
     * 
     * @return Comparison
     */
    function alphaNumeric();
    
    /**
     * 
     * @return Comparison
     */
    function eq();

    /**
     * 
     * @return Comparison
     */
    function neq();

    /**
     * 
     * @return Comparisons
     */
    function val($value);

    /**
     * 
     * @return Comparisons
     */
    function var($varName);
}

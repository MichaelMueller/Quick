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
     * @return trur
     */
    function eval( $array );

    /**
     * 
     * @return BooleanExpression
     */
    function and();

    /**
     * 
     * @return BooleanExpression
     */
    function or();

    /**
     * 
     * @return BooleanExpression
     */
    function minLength( $minLength );

    /**
     * 
     * @return BooleanExpression
     */
    function alphaNumeric();

    /**
     * 
     * @return BooleanExpression
     */
    function equalsVar( $otherVarName );

    /**
     * 
     * @return BooleanExpression
     */
    function group();

    /**
     * 
     * @return BooleanExpression|null the parent or null if we are at the root
     */
    function endGroup();
}

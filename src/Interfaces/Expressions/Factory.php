<?php

namespace Qck\Interfaces\Expressions;

interface Factory
{

    /**
     * 
     * @param string $key
     * @return ValueExpression
     */
    function var( $key );

    /**
     * 
     * @param mixed $value
     * @return ValueExpression
     */
    function value( $value );

    /**
     * 
     * @param ValueExpression $val
     * @return ValueExpression
     */
    function strlen( ValueExpression $val );

    /**
     * 
     * @param ValueExpression $val
     * @param string $pattern
     * @return ValueExpression
     */
    function regex( ValueExpression $val, $pattern );

    /**
     * 
     * @param ValueExpression $left
     * @param ValueExpression $right
     * @return BooleanExpression
     */
    function eq( ValueExpression $left, ValueExpression $right );


    /**
     * 
     * @param ValueExpression $left
     * @param ValueExpression $right
     * @return BooleanExpression
     */
    function neq( ValueExpression $left, ValueExpression $right );

    /**
     * 
     * @param ValueExpression $left
     * @param ValueExpression $right
     * @return BooleanExpression
     */
    function gt( ValueExpression $left, ValueExpression $right );

    /**
     * 
     * @param ValueExpression $left
     * @param ValueExpression $right
     * @return BooleanExpression
     */
    function geq( ValueExpression $left, ValueExpression $right );

    /**
     * 
     * @param ValueExpression $left
     * @param ValueExpression $right
     * @return BooleanExpression
     */
    function lt( ValueExpression $left, ValueExpression $right );

    /**
     * 
     * @param ValueExpression $left
     * @param ValueExpression $right
     * @return BooleanExpression
     */
    function leq( ValueExpression $left, ValueExpression $right );
}

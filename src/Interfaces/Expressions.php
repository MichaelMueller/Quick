<?php

namespace Qck\Interfaces;

/**
 * represents constraint(s) of a variable
 */
interface Expressions extends Expression
{

    /**
     * prepares or ends a comparison
     * @param string $varName
     * @return Expressions
     */
    function var( $varName, $filter = false );
    
    /**
     * prepares or ends a comparison
     * @param string $varName
     * @return Expressions
     */
    function default( $varName, $defaultValue );
    
    /**
     * 
     * @param type $varName
     * @param type $plainTextPassword
     * @return Expressions
     */
    function passwordVerify( $varName, $plainTextPassword );
    
    /**
     * prepares or ends a comparison
     * @param string $value
     * @return Expressions
     */
    function val( $value );

    /**
     * prepares or ends a comparison
     * @param string $varName
     * @return Expressions
     */
    function length( $varName, $filter = false );

    /**
     * prepares an equals comparsion
     * @return Expressions
     */
    function eq();

    /**
     * prepares an equals comparsion
     * @return Expressions
     */
    function ne();

    /**
     * prepares an equals comparsion
     * @return Expressions
     */
    function gt();

    /**
     * prepares an equals comparsion
     * @return Expressions
     */
    function ge();

    /**
     * prepares an equals comparsion
     * @return Expressions
     */
    function lt();

    /**
     * prepares an equals comparsion
     * @return Expressions
     */
    function le();

    /**
     * prepares an equals comparsion
     * @return Expressions
     */
    function matches();

    /**
     * adds a new subgroup (operator precedence) or just return this
     * @return Expressions
     */
    function and();

    /**
     * closes the and subgroup, return this
     * @return Expressions
     */
    function or();

    /**
     * adds a new group and returns it
     * @return Expressions
     */
    function group( $error = null, $evaluateAll = false );

    /**
     * close the group and return the parent
     * @return Expressions
     */
    function closeGroup();
}

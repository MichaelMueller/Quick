<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Table
{

    /**
     * 
     * @return mixed an auto generated id or null or false if $record contains invalid values
     */
    function create( array $record );

    /**
     * 
     * @return Select
     */
    function select();

    /**
     * 
     * @param BooleanExpression $booleanExpression (could be null though)
     * @param array $record
     * @return mixed number of affected rows or false if $record contains invalid values
     */
    function update( BooleanExpression $booleanExpression, array $record );

    /**
     * 
     * @param Conditions $constraints (could be null though)
     * @return int number of affected rows
     */
    function delete( BooleanExpression $booleanExpression );
}

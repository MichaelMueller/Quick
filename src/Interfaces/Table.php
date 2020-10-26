<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Table
{

    const ORDER_ASC  = 0;
    const ORDER_DESC = 1;

    /**
     * 
     * @return mixed an auto generated id or null
     */
    function create( array $record );

    /**
     * 
     * @return Select
     */
    function select();

    /**
     * 
     * @param Conditions $constraints (could be null though)
     * @param array $record
     * @return int number of affected rows
     */
    function update( Conditions $constraints, array $record );

    /**
     * 
     * @param Conditions $constraints (could be null though)
     * @return int number of affected rows
     */
    function delete( Conditions $constraints );
}

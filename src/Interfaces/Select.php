<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Select
{

    /**
     * 
     * @return Select
     */
    function columns( ...$columns );

    /**
     * 
     * @return Select
     */
    function where( BooleanExpression $booleanExpression );

    /**
     * 
     * @return Select
     */
    function limit( $limit );

    /**
     * 
     * @return Select
     */
    function offset( $offset );

    /**
     * 
     * @return Select
     */
    function orderBy( $orderColumn, $orderDescending = false );

    /**
     * 
     * @return Select
     */
    function fetchRow();

    /**
     * 
     * @return Select
     */
    function fetchColumn();

    /**
     * 
     * @return mixed
     */
    function exec();
}

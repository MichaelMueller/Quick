<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Select
{

    const ORDER_ASC  = 0;
    const ORDER_DESC = 1;

    /**
     * 
     * @return Select
     */
    function columns( ...$columns );

    /**
     * 
     * @return Select
     */
    function where( Conditions $conditions );

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

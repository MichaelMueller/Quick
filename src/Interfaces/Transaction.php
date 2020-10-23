<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Transaction 
{

    /**
     * Persist changes
     */
    function commit();

    /**
     * 
     * @param mixed $id
     * @return array
     */
    function rollback();

    
}

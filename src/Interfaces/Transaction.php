<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Transaction 
{
    /**
     * 
     * @param mixed $id
     * @return array
     */
    function rollback();

    
}

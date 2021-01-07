<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface ErrorSource
{

    /**
     * @return Error[]
     */
    function errors();
    
    /**
     * @return Exception
     */
    function exception();
    
    /**
     * @return ErrorSource
     */
    function error($text, $relatedKey=null);

    /**
     * @return string
     */
    function name();
        
    /**
     * @return string
     */
    function __toString();
}

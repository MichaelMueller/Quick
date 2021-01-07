<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface Error
{

    /**
     * @return string
     */
    function text();

    /**
     * @return string
     */
    function relatedKey();
        
    /**
     * @return string
     */
    function __toString();
}

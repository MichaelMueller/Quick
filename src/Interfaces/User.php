<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface User
{

    /**
     * 
     * @return string
     */
    function id();

    /**
     * 
     * @return string
     */
    function email();

    /**
     * 
     * @return string
     */
    function name();
}

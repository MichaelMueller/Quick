<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface Exception extends \Throwable
{

    /**
     * @return ErrorSource[]
     */
    function errorSources();

    /**
     * @return ErrorSource
     */
    function errorSource($name);

    /**
     * @return void
     */
    function throw();
}

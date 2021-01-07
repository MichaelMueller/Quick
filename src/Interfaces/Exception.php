<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface Exception extends \Throwable
{

    /**
     * @return Exception
     */
    function error($text, ...$args);

    /**
     * @return Exception
     */
    function argError($text, $relatedKey, ...$args);
    
    /**
     * @return Exception $this
     */
    function setHttpReturnCode($returnCode = HttpResponse::EXIT_CODE_INTERNAL_ERROR);

    /**
     * @return ErrorSet
     */
    function errorSet($name = null);

    /**
     * @return void
     */
    function throw();
}

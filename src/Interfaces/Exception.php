<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface Exception
{

    /**
     * assertion
     */    
    function assert($condition, $error);
    
    /**
     * @return Exception
     */
    function error($text, ...$args);

    /**
     * @return Exception
     */
    function argError($text, $relatedKey, ...$args);

    /**
     * @return Error[]
     */
    function errors();

    /**
     * @param int $httpReturnCode
     * @return Exception $this
     */
    function setHttpReturnCode($httpReturnCode = HttpResponse::EXIT_CODE_INTERNAL_ERROR);

    /**
     * 
     * @param int $returnCode
     */
    function setReturnCode($returnCode = -1);

    /**
     * @return void
     */
    function throw();
}

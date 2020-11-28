<?php

namespace Qck\Interfaces\Comparison;

/**
 * 
 * @author muellerm
 */
interface Start
{

    /**
     * 
     * @return LeftVariable
     */
    function variable( ...$keys );

    /**
     * 
     * @return LeftOperand
     */
    function val( $val );

    /**
     * 
     * @return Start
     */
    function parantheses();
}

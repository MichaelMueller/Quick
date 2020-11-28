<?php

namespace Qck\Interfaces\Comparison;

/**
 * 
 * @author muellerm
 */
interface RightOperand
{

    /**
     * @return RightVariable
     */
    function variable( ...$keys );

    /**
     * 
     * @return End
     */
    function val( $val );
}

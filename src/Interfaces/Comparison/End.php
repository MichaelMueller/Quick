<?php

namespace Qck\Interfaces\Comparison;

/**
 * 
 * @author muellerm
 */
interface End extends \Qck\Interfaces\BooleanExpression
{

    /**
     * 
     * @return End
     */
    function closeParantheses();

    /**
     * 
     * @return Start
     */
    function and();

    /**
     * 
     * @return Start
     */
    function or();
}

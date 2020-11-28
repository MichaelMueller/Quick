<?php

namespace Qck\Interfaces\Comparison;

/**
 * 
 * @author muellerm
 */
interface LeftOperand
{

    /**
     * @return RightOperand
     */
    function equals();

    /**
     * @return RightOperand
     */
    function notEquals();

    /**
     * @return RightOperand
     */
    function greater();

    /**
     * @return RightOperand
     */
    function greaterEquals();

    /**
     * @return RightOperand
     */
    function less();

    /**
     * @return RightOperand
     */
    function lessEquals();

    /**
     * @return RightOperand
     */
    function matches();
}

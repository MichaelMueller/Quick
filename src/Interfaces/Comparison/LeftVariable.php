<?php

namespace Qck\Interfaces\Comparison;

/**
 * 
 * @author muellerm
 */
interface LeftVariable extends LeftOperand
{

    /**
     * @return LeftOperand
     */
    function lower();

    /**
     * @return LeftOperand
     */
    function length();
}

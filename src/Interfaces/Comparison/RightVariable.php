<?php

namespace Qck\Interfaces\Comparison;

/**
 * 
 * @author muellerm
 */
interface RightVariable extends End
{

    /**
     * @return End
     */
    function lower();

    /**
     * @return End
     */
    function length();
}

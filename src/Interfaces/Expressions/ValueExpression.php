<?php

namespace Qck\Interfaces\Expressions;

interface ValueExpression
{

    /**
     * @return int|bool|float|string
     */
    function get( array $array );
}

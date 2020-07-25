<?php

namespace Qck\Interfaces\Expressions;

interface BooleanExpression
{

    /**
     * @return bool
     */
    function eval( array $array );
}
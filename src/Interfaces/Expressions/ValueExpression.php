<?php

namespace Qck\Interfaces\Expressions;

/**
 *
 * @author muellerm
 */
interface ValueExpression extends Expression
{

    /**
     * Gets a value from this ValueExpression
     * @param array $Data needed by \Qck\Interfaces\Expressions\Var_
     * @param array $FilteredData needed by \Qck\Interfaces\Expressions\Var_
     * @return mixed
     */
    function getValue( array $Data = [], array &$FilteredData = [] );
}

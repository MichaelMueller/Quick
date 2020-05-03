<?php

namespace Qck\Interfaces\Expressions;

/**
 * Base interface for boolean expressions that can be evaluated
 * @author muellerm
 */
interface BooleanExpression extends Expression
{

    /**
     * Evaluates Data on an array
     * @param array $Data the actual data array
     * @param array $FilteredArray output array giving only an array which contains the Fields that were evaluated by this expression and its children
     * @param array $FailedExpressions output array giving all failed expressions in sequential order
     * @return bool true if evaluation was ok, false otherwise
     */
    public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] );
}

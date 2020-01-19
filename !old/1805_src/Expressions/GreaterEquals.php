<?php

namespace qck\Expressions;

/**
 *
 * @author muellerm
 */
class GreaterEquals extends Comparison
{

  function __construct( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    parent::__construct( $LeftOperand, $RightOperand );
  }

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    return $this->LeftOperand->evaluate( $Data, $FilteredArray, $FailedExpressions ) >= $this->RightOperand->evaluate( $Data, $FilteredArray, $FailedExpressions );
  }

  public function getOperator( \qck\Sql\Interfaces\DbDictionary $Dictionary )
  {
    return ">=";
  }
}

<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
class NotEquals extends Comparison
{

  function __construct( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    parent::__construct( $LeftOperand, $RightOperand );
  }

  public function evaluateProxy( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    return $this->LeftOperand->evaluate( $Data, $FilteredArray, $FailedExpressions ) != $this->RightOperand->evaluate( $Data, $FilteredArray, $FailedExpressions );
  }

  public function getOperator( \Qck\Interfaces\Sql\DbDialect $Dictionary )
  {
    return "!=";
  }
}

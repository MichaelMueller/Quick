<?php

namespace qck\db\expressions;

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

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    return $this->LeftOperand->evaluate( $Data, $FilteredArray, $FailedExpressions ) != $this->RightOperand->evaluate( $Data, $FilteredArray, $FailedExpressions );
  }
}
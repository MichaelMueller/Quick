<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
class Regexp extends Comparison
{

  function __construct( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    parent::__construct( $LeftOperand, $RightOperand );
  }

  public function getOperator( \Qck\Interfaces\Sql\DbDialect $Dictionary )
  {
    return $Dictionary->getRegExpOperator();
  }

  public function evaluateProxy( array $Data, &$FilteredArray = array (),
                                 &$FailedExpressions = array () )
  {
    return preg_match( $this->LeftOperand->evaluate( $Data, $FilteredArray, $FailedExpressions ), $this->RightOperand->evaluate( $Data, $FilteredArray, $FailedExpressions ) ) == true;
  }
}

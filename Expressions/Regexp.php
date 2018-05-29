<?php

namespace qck\Expressions;

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

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    return preg_match( $this->LeftOperand->evaluate( $Data, $FilteredArray, $FailedExpressions ), $this->RightOperand->evaluate( $Data, $FilteredArray, $FailedExpressions ) ) == true;
  }

  public function getOperator( \qck\Sql\Interfaces\DbDictionary $Dictionary )
  {
    return $Dictionary->getRegExpOperator();
  }
}

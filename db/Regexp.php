<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Regexp extends abstracts\Comparison
{

  function __construct( abstracts\ValueExpression $LeftOperand, abstracts\ValueExpression $RightOperand )
  {
    parent::__construct( $LeftOperand, $RightOperand );
  }

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    return preg_match( $this->LeftOperand->evaluate( $Data, $FilteredArray, $FailedExpressions ), $this->RightOperand->evaluate( $Data, $FilteredArray, $FailedExpressions ) ) == true;
  }
}

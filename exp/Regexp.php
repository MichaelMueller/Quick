<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
class Regexp extends Comparison
{

  function __construct( AtomicExpression $LeftOperand, AtomicExpression $RightOperand )
  {
    parent::__construct( $LeftOperand, $RightOperand );
  }

  public function evaluate( array $Data )
  {
    return preg_match( $this->LeftOperand->evaluate( $Data ), $this->RightOperand->evaluate( $Data ) ) == true;
  }
}

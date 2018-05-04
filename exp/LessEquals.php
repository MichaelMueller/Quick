<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
class LessEquals extends Comparison
{

  function __construct( AtomicExpression $LeftOperand,
                        AtomicExpression $RightOperand )
  {
    parent::__construct( $LeftOperand, $RightOperand );
  }

  public function evaluate( array $Data )
  {
    return $this->LeftOperand->evaluate($Data) <= $this->RightOperand->evaluate($Data);
  }
}

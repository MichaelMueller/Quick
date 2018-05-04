<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
abstract class Comparison extends BooleanExpression
{

  function __construct( AtomicExpression $LeftOperand,
                        AtomicExpression $RightOperand )
  {
    $this->LeftOperand = $LeftOperand;
    $this->RightOperand = $RightOperand;
  }

  function getLeftOperand()
  {
    return $this->LeftOperand;
  }

  function getRightOperand()
  {
    return $this->RightOperand;
  }


  /**
   *
   * @var AtomicExpression 
   */
  protected $LeftOperand;

  /**
   *
   * @var AtomicExpression 
   */
  protected $RightOperand;

}

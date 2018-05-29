<?php

namespace qck\GraphStorage\Sql\Expressions;

/**
 *
 * @author muellerm
 */
abstract class Comparison extends BooleanExpression
{
  abstract function getOperator( \qck\GraphStorage\Sql\Dictionary $Dictionary );

  function __construct( ValueExpression $LeftOperand, ValueExpression $RightOperand )
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

  public function toSql( $DriverName, array &$Params = array () )
  {
    return $this->LeftOperand->toSql( $DriverName, $Params ) . " ".$this->getOperator( $Dictionary )." " . $this->RightOperand->toSql( $DriverName, $Params );
  }

  /**
   *
   * @var ValueExpression
   */
  protected $LeftOperand;

  /**
   *
   * @var ValueExpression 
   */
  protected $RightOperand;

}

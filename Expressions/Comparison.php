<?php

namespace qck\Expressions;

/**
 *
 * @author muellerm
 */
abstract class Comparison extends BooleanExpression
{
  abstract function getOperator( \qck\Sql\Interfaces\DbDictionary $Dictionary );

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

  public function toSql( \qck\Sql\Interfaces\DbDictionary $DbDictionary, array &$Params = array () )
  {
    return $this->LeftOperand->toSql( $DbDictionary, $Params ) . " ".$this->getOperator( $DbDictionary )." " . $this->RightOperand->toSql( $DbDictionary, $Params );
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

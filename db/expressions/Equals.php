<?php

namespace qck\db\expressions;

/**
 *
 * @author muellerm
 */
class Equals extends Comparison
{

  function __construct( ValueExpression $LeftOperand,
                        ValueExpression $RightOperand )
  {
    parent::__construct( $LeftOperand, $RightOperand );
  }

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    $eval = $this->LeftOperand->evaluate( $Data, $FilteredArray, $FailedExpressions ) == $this->RightOperand->evaluate( $Data, $FilteredArray, $FailedExpressions );
    if ( !$eval )
      $FailedExpressions[] = $this;
    return $eval;
  }

  public function toSql( $DriverName, array &$Params = array () )
  {
    return $this->LeftOperand->toSql( $DriverName, $Params ) . " = " . $this->RightOperand->toSql( $DriverName, $Params );
  }
}

<?php

namespace qck\GraphStorage\Sql\Expressions;

/**
 *
 * @author muellerm
 */
class Strlen extends SingleParamFunction
{

  function __construct( ValueExpression $Param )
  {
    parent::__construct( $Param );
  }

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    return mb_strlen( $this->Param->evaluate( $Data, $FilteredArray, $FailedExpressions ) );
  }

  public function toSql( \qck\GraphStorage\Sql\Dictionary $Dictionary, array &$Params = array () )
  {
    return $Dictionary->getStrlenFunctionName() . " ( " . $this->Param->toSql( $Dictionary, $Params ) . " ) ";
  }
}

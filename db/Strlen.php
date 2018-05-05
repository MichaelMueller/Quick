<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Strlen extends abstracts\SingleParamFunction
{

  function __construct( abstracts\ValueExpression $Param )
  {
    parent::__construct( $Param );
  }

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    return mb_strlen( $this->Param->evaluate( $Data, $FilteredArray, $FailedExpressions ) );
  }
}

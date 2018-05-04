<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
class Strlen extends SingleParamFunction
{

  function __construct( AtomicExpression $Param )
  {
    parent::__construct( $Param );
  }

  public function evaluate( array $Data )
  {
    return mb_strlen( $this->Param->evaluate( $Data ) );
  }
}

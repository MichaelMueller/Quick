<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
class Not extends BooleanExpression
{

  function __construct( BooleanExpression $BooleanExpression )
  {
    $this->BooleanExpression = $BooleanExpression;
  }

  public function evaluate( array $Data )
  {
    return !$this->BooleanExpression->evaluate( $Data );
  }

  /**
   *
   * @var BooleanExpression 
   */
  protected $BooleanExpression;

}

<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Not extends abstracts\BooleanExpression
{

  function __construct( abstracts\BooleanExpression $BooleanExpression )
  {
    $this->BooleanExpression = $BooleanExpression;
  }

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    return !$this->BooleanExpression->evaluate( $Data, $FilteredArray, $FailedExpressions );
  }

  /**
   *
   * @var abstracts\BooleanExpression 
   */
  protected $BooleanExpression;

}

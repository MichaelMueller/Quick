<?php

namespace qck\db\expressions;

/**
 *
 * @author muellerm
 */
class Value extends ValueExpression
{

  function __construct( $Value )
  {
    $this->Value = $Value;
  }

  function getValue()
  {
    return $this->Value;
  }

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    return $this->Value;
  }

  /**
   *
   * @var ValueExpression
   */
  protected $Value;

}

<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Value extends abstracts\ValueExpression
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
   * @var abstracts\ValueExpression
   */
  protected $Value;

}

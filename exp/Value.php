<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
class Value extends AtomicExpression
{

  function __construct( $Value )
  {
    $this->Value = $Value;
  }

  function getValue()
  {
    return $this->Value;
  }

  public function evaluate( array $Data )
  {
    return $this->Value;
  }

  /**
   *
   * @var AtomicExpression
   */
  protected $Value;

}

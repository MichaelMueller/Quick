<?php

namespace qck\Expressions;

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

  public function toSql( \qck\Sql\Interfaces\DbDictionary $Dictionary,
                         array &$Params = array () )
  {
    $Params[] = $this->Value;
    return "?";
  }

  /**
   *
   * @var mixed
   */
  protected $Value;

}

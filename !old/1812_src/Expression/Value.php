<?php

namespace Qck\Expression;

/**
 *
 * @author muellerm
 */
class Value implements Interfaces\ValueExpression
{

  function __construct( $Value )
  {
    $this->Value = $Value;
  }

  function getValue( array $Data = [], array &$FilteredData = [] )
  {
    return $this->Value;
  }

  public function toSql( \Qck\Interfaces\Sql\DbDialect $Dictionary,
                         array &$Params = array () )
  {
    $Params[] = $this->Value;
    return "?";
  }

  function __toString()
  {
    return strval($this->Value);
  }
  /**
   *
   * @var mixed
   */
  protected $Value;

}

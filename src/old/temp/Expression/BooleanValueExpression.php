<?php

namespace Qck\Expression;

/**
 *
 * @author muellerm
 */
class BooleanValueExpression extends BooleanExpression
{

  function __construct( $Value )
  {
    $this->Value = $Value;
  }

  public function evaluateProxy( array $Data, &$FilteredArray = [] )
  {
    return boolval( $this->Value );
  }

  public function toSql( \Qck\Interfaces\Sql\DbDialect $Dictionary,
                         array &$Params = array () )
  {
    return boolval( $this->Value ) == true ? $Dictionary->getTrueLiteral() : $Dictionary->getFalseLiteral();
  }

  function __toString()
  {
    return strval( boolval( $this->Value ) );
  }

  protected $Value;

}

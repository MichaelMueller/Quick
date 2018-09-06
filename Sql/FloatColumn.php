<?php

namespace Qck\Sql;

use Qck\Expressions\Expression as x;

/**
 *
 * @author muellerm
 */
class FloatColumn extends Column
{

  function __construct( $Name, $min = INF, $max = INF )
  {
    parent::__construct( $Name );
    $this->min = $min;
    $this->max = $max;
  }

  public function getDatatype( \Qck\Interfaces\Sql\DbDialect $SqlDbDialect )
  {
    return $SqlDbDialect->getFloatDatatype();
  }

  public function isValid( $value )
  {
    return $value >= $this->Min && $value <= $this->Max;
  }

  public function createExpression()
  {
    $leThanMax = x::le( x::id( $this->getName() ), x::val($this->max) );
    $geThanMin = x::ge(x::id($this->getName()), x::val($this->min));
    return x::and_( $leThanMax, $geThanMin );
  }

  public function render( \Qck\Interfaces\Html\Page $Page )
  {
    return $Page->createElement( "input", [ "name" => $this->getName(), "type" => "number" ] );
  }
  
  protected $min;
  protected $max;

}

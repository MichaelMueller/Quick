<?php

namespace Qck\Expression;

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

  public function evaluateProxy( array $Data, &$FilteredArray = [],
                                 &$FailedExpressions = [] )
  {
    return !$this->BooleanExpression->evaluate( $Data, $FilteredArray, $FailedExpressions );
  }

  public function toSql( \Qck\Interfaces\Sql\DbDialect $Dictionary,
                         array &$Params = array () )
  {
    return "not " . $this->BooleanExpression->toSql( $Dictionary, $Params );
  }

  function __toString()
  {
    return "not ".$this->BooleanExpression->__toString();
  }
  
  /**
   *
   * @var BooleanExpression 
   */
  protected $BooleanExpression;

}

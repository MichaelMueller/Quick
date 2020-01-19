<?php

namespace Qck\Expression;

/**
 *
 * @author muellerm
 */
class Or_ extends BooleanChain
{

  static function create( array $Expressions = [] )
  {
    return new Or_( $Expressions );
  }

  function __construct( array $Expressions = [] )
  {
    $this->Expressions = $Expressions;
  }

  public function evaluateProxy( array $Data, &$FilteredArray = [],
                                 &$FailedExpressions = [] )
  {
    $eval = false;
    foreach ( $this->Expressions as $Expression )
      $eval = $eval || $Expression->evaluate( $Data, $FilteredArray, $FailedExpressions );

    return $eval;
  }

  public function getOperator( \Qck\Interfaces\Sql\DbDialect $Dictionary )
  {
    return "or";
  }

  public function getOperatorString()
  {
    return "or";
  }
}

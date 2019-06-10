<?php

namespace Qck\Expression;

/**
 *
 * @author muellerm
 */
class And_ extends BooleanChain
{

  function __construct( array $Expressions = array (), $EvaluateAll = false )
  {
    $this->Expressions = $Expressions;
    $this->EvaluateAll = $EvaluateAll;
  }

  public function evaluateProxy( array $Data, &$FilteredArray = [],
                                 &$FailedExpressions = [] )
  {
    $eval = true;
    foreach ( $this->Expressions as $Expression )
    {
      $eval = $eval && $Expression->evaluate( $Data, $FilteredArray, $FailedExpressions );
      if ( !$eval && $this->EvaluateAll == false )
        break;
    }
    return $eval;
  }

  public function getOperator( \Qck\Interfaces\Sql\DbDialect $Dictionary )
  {
    return $Dictionary->getAndOperator();
  }

  public function getOperatorString()
  {
    return "and";
  }

  protected $EvaluateAll;

}

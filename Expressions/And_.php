<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
class And_ extends BooleanChain
{

  function __construct( array $Expressions = [], $EvaluateAll = false )
  {
    parent::__construct( $Expressions );
    $this->EvaluateAll = $EvaluateAll;
  }

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    $eval = true;
    foreach ( $this->Expressions as $Expression )
    {
      $eval = $eval && $Expression->evaluate( $Data, $FilteredArray, $FailedExpressions );
      if ( !$eval && $this->EvaluateAll == false )
        break;
    }
    if ( !$eval )
      $FailedExpressions[] = $this;
    return $eval;
  }

  public function getOperator( \Qck\Interfaces\DbDictionary $Dictionary )
  {
    return "and";
  }

  protected $EvaluateAll;

}

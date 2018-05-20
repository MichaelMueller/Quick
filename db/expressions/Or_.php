<?php

namespace qck\db\expressions;

/**
 *
 * @author muellerm
 */
class Or_ extends BooleanChain
{

  function __construct( array $Expressions = [] )
  {
    parent::__construct( $Expressions );
  }

  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] )
  {
    $eval = false;
    foreach ( $this->Expressions as $Expression )
      $eval = $eval || $Expression->evaluate( $Data, $FilteredArray, $FailedExpressions );

    return $eval;
  }
}

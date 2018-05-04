<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
class And_ extends BooleanChain
{

  function __construct( array $Expressions = [] )
  {
    parent::__construct( $Expressions );
  }

  public function evaluate( array $Data )
  {
    $eval = true;
    foreach ( $this->Expressions as $Expression )
    {
      $eval = $eval && $Expression->evaluate( $Data );
      if ( !$eval )
        break;
    }
    return $eval;
  }
}

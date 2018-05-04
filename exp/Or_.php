<?php

namespace qck\exp;

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

  public function evaluate( array $Data )
  {
    $eval = false;
    foreach ( $this->Expressions as $Expression )
      $eval = $eval || $Expression->evaluate( $Data );
    
    return $eval;
  }
}

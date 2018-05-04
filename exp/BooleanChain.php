<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
abstract class BooleanChain extends BooleanExpression
{

  function __construct( array $Expressions = [] )
  {
    foreach ( $Expressions as $Expression )
      $this->add( $Expression );
  }

  function add( Expression $Expression )
  {
    $this->Expressions[] = $Expression;
  }

  protected $Expressions;

}

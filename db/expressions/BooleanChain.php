<?php

namespace qck\db\expressions;

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

  function add( \qck\db\interfaces\Expression $Expression )
  {
    $this->Expressions[] = $Expression;
  }

  protected $Expressions;

}

<?php

namespace Qck\Interfaces\Expressions;

/**
 * Base class for composite boolean expression (And, or, ...)
 * @author muellerm
 */
interface BooleanChain extends BooleanExpression
{

  /**
   * Adds another boolean expression to this chain
   * @param \Qck\Interfaces\Expressions\BooleanExpression $Expression
   */
  function add( BooleanExpression $Expression );
}

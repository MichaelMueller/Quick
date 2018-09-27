<?php

namespace Qck\Expression\Interfaces;

/**
 * Base class for composite boolean expression (And, or, ...)
 * @author muellerm
 */
interface BooleanChain extends BooleanExpression
{

  /**
   * Adds another boolean expression to this chain
   * @param \Qck\Expression\Interfaces\BooleanExpression $Expression
   */
  function add( BooleanExpression $Expression );
}

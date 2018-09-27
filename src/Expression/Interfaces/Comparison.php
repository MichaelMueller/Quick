<?php

namespace Qck\Expression\Interfaces;

/**
 * Base class for composite boolean expression (And, or, ...)
 * @author muellerm
 */
interface Comparison extends BooleanExpression
{

  /**
   * @return ValueExpression
   */
  function getLeft();

  /**
   * @return ValueExpression
   */
  function getRight();
}

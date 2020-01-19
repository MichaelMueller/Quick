<?php

namespace Qck\Interfaces\Expressions;

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

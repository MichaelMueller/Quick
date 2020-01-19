<?php

namespace Qck\Interfaces\Expressions;

/**
 * Base class for a Variable Expression
 * @author muellerm
 */
interface Variable extends ValueExpression
{

  /**
   * @return string
   */
  function getName();
}

<?php

namespace Qck\Expression\Interfaces;

/**
 *
 * @author muellerm
 */
interface ValueExpression extends Expression
{
  /**
   * @return mixed
   */
  function getValue();
}

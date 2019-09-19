<?php

namespace Qck\Interfaces\Expressions;

/**
 *
 * @author muellerm
 */
interface SingleParamFunction extends ValueExpression
{

  /**
   * @return ValueExpression
   */
  function getParam();
}

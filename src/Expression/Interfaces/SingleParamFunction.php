<?php

namespace Qck\Expression\Interfaces;

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

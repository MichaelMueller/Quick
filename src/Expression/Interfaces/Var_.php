<?php

namespace Qck\Expression\Interfaces;

/**
 * Base class for a Variable Expression
 * @author muellerm
 */
interface Var_ extends ValueExpression
{

  /**
   * @return string
   */
  function getName();
}

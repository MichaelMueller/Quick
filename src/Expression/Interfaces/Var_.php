<?php

namespace Qck\Expression\Interfaces;

/**
 * Base class for a Variable Expression
 * @author muellerm
 */
interface Var_ extends ValueExpression
{
  /**
   * @return string The Name of the Var
   */
  function getName();
  
  /**
   * @see BooleanExpression::filterVar($Data)
   * @return bool Whether to use this Var in a filtered Array   * 
   */
  function filterOut();
}

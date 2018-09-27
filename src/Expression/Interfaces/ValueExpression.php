<?php

namespace Qck\Expression\Interfaces;

/**
 *
 * @author muellerm
 */
interface ValueExpression extends Expression
{

  /**
   * Gets a value from this ValueExpression
   * @param array $Data needed by \Qck\Expression\Interfaces\Var_
   * @param array $FilteredData needed by \Qck\Expression\Interfaces\Var_
   * @return mixed
   */
  function getValue( array $Data = [], array &$FilteredData = [] );
}

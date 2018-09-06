<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
class True_ extends BooleanExpression
{

  public function evaluateProxy( array $Data, &$FilteredArray = [],
                                 &$FailedExpressions = [] )
  {
    return true;
  }

  public function toSql( \Qck\Interfaces\Sql\DbDialect $Dictionary,
                         array &$Params = array () )
  {
    return $Dictionary->getTrueLiteral();
  }
}

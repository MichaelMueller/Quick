<?php

namespace Qck\Expressions;

/**
 *
 * @author muellerm
 */
abstract class BooleanExpression extends Expression
{

  abstract function evaluateProxy( array $Data, &$FilteredArray = array (),
                                   &$FailedExpressions = array () );

  function evaluate( array $Data, &$FilteredArray = array (),
                     &$FailedExpressions = array () )
  {
    $eval = $this->evaluateProxy( $Data, $FilteredArray, $FailedExpressions );

    if ( !$eval )
      $FailedExpressions[] = $this;
  }
}

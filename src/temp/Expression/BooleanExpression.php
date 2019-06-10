<?php

namespace Qck\Expression;

/**
 *
 * @author muellerm
 */
abstract class BooleanExpression implements Interfaces\BooleanExpression
{

  abstract function evaluateProxy( array $Data, &$FilteredArray = array (),
                                   &$FailedExpressions = array () );

  function evaluate( array $Data, &$FilteredArray = array (),
                     &$FailedExpressions = array () )
  {
    $eval = $this->evaluateProxy( $Data, $FilteredArray, $FailedExpressions );

    if ( !$eval )
      $FailedExpressions[] = $this;

    return $eval;
  }

  function filterVar( array $Data, &$FailedExpressions = [] )
  {
    $FilteredArray = [];
    $IsValid = $this->evaluate( $Data, $FilteredArray, $FailedExpressions );
    return $IsValid ? $FilteredArray : false;
  }

  function filterRequest( \Qck\Interfaces\Request $Request, &$FailedExpressions = [] )
  {
    return $this->filterVar( $Request->getData(), $FailedExpressions );
  }

  function evaluateRequest( \Qck\Interfaces\Request $Request,
                            &$FilteredArray = array (), &$FailedExpressions = array () )
  {
    return $this->evaluate( $Request->getData(), $FilteredArray, $FailedExpressions );
  }
}

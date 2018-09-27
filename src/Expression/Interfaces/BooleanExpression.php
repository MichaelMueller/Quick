<?php

namespace Qck\Expression\Interfaces;

/**
 * Base interface for boolean expressions that can be evaluated
 * @author muellerm
 */
interface BooleanExpression extends Expression
{

  /**
   * Evaluates Data on an array
   * @param array $Data the actual data array
   * @param array $FilteredArray output array giving only an array which contains the Fields that were evaluated by this expression and its children
   * @param array $FailedExpressions output array giving all failed expressions in sequential order
   * @return bool true if evaluation was ok, false otherwise
   */
  public function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] );

  /**
   * Analogous to PHP's filterVars
   * @param array $Data
   * @param array $FailedExpressions
   */
  function filterVar( array $Data, &$FailedExpressions = [] );

  /**
   * Same as above using the Request interface
   * @param \Qck\App\Interfaces\Request $Request \Qck\App\Interfaces\Request::getParams() will be used
   * @param array $FilteredArray see above
   * @param array $FailedExpressions see above
   * @return bool true if evaluation was ok, false otherwise
   */
  public function evaluateRequest( \Qck\App\Interfaces\Request $Request,
                                   &$FilteredArray = [], &$FailedExpressions = [] );

  /**
   * Analogous to PHP's filterVars
   * @param \Qck\App\Interfaces\Request $Request
   * @param array $FailedExpressions
   */
  function filterRequest( \Qck\App\Interfaces\Request $Request, &$FailedExpressions = [] );
}

<?php

namespace qck\Expressions\Abstracts;

use qck\Expressions\ValueExpression;

/**
 *
 * @author muellerm
 */
abstract class Expression implements \qck\Expressions\Interfaces\Expression
{

  function filterVar( array $Data, &$FailedExpressions = [] )
  {
    $FilteredArray = [];
    $IsValid = $this->evaluate( $Data, $FilteredArray, $FailedExpressions );
    return $IsValid ? $FilteredArray : false;
  }

  static function and_( array $Expressions = [], $EvaluateAll = false )
  {
    return new \qck\Expressions\And_( $Expressions, $EvaluateAll );
  }

  static function strlen( ValueExpression $Param )
  {
    return new \qck\Expressions\Strlen( $Param );
  }

  static function id( $Name, $UseForFilteredArray = true )
  {
    return new \qck\Expressions\Identifier( $Name, $UseForFilteredArray );
  }

  static function val( $Value )
  {
    return new \qck\Expressions\Value( $Value );
  }

  static function gt( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \qck\Expressions\Greater( $LeftOperand, $RightOperand );
  }

  static function ge( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \qck\Expressions\GreaterEquals( $LeftOperand, $RightOperand );
  }

  static function eq( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \qck\Expressions\Equals( $LeftOperand, $RightOperand );
  }

  static function ne( BooleanExpression $BooleanExpression )
  {
    return new \qck\Expressions\Not( $BooleanExpression );
  }

  static function lt( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \qck\Expressions\Less( $LeftOperand, $RightOperand );
  }

  static function le( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \qck\Expressions\LessEquals( $LeftOperand, $RightOperand );
  }

}

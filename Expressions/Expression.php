<?php

namespace Qck\Expressions;

use Qck\Expressions\ValueExpression;

/**
 *
 * @author muellerm
 */
abstract class Expression implements \Qck\Interfaces\Expression
{

  function filterVar( array $Data, &$FailedExpressions = [] )
  {
    $FilteredArray = [];
    $IsValid = $this->evaluate( $Data, $FilteredArray, $FailedExpressions );
    return $IsValid ? $FilteredArray : false;
  }

  static function and_( array $Expressions = [], $EvaluateAll = false )
  {
    return new \Qck\Expressions\And_( $Expressions, $EvaluateAll );
  }

  static function strlen( ValueExpression $Param )
  {
    return new \Qck\Expressions\Strlen( $Param );
  }

  static function id( $Name, $UseForFilteredArray = true )
  {
    return new \Qck\Expressions\Identifier( $Name, $UseForFilteredArray );
  }

  static function val( $Value )
  {
    return new \Qck\Expressions\Value( $Value );
  }

  static function regexp( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expressions\Regexp($LeftOperand, $RightOperand );
  }
  
  static function gt( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expressions\Greater( $LeftOperand, $RightOperand );
  }

  static function ge( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expressions\GreaterEquals( $LeftOperand, $RightOperand );
  }

  static function eq( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expressions\Equals( $LeftOperand, $RightOperand );
  }

  static function ne( BooleanExpression $BooleanExpression )
  {
    return new \Qck\Expressions\Not( $BooleanExpression );
  }

  static function lt( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expressions\Less( $LeftOperand, $RightOperand );
  }

  static function le( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expressions\LessEquals( $LeftOperand, $RightOperand );
  }
}

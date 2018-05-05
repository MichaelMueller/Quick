<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Expressions
{

  static function and_( array $Expressions = [], $EvaluateAll=false )
  {
    return new And_ ( $Expressions, $EvaluateAll );
  }

  static function strlen( abstracts\ValueExpression $Param )
  {
    return new Strlen( $Param );
  }

  static function id( $Name )
  {
    return new Identifier( $Name );
  }

  static function val( $Value )
  {
    return new Value( $Value );
  }

  static function gt( abstracts\ValueExpression $LeftOperand, abstracts\ValueExpression $RightOperand )
  {
    return new Greater( $LeftOperand, $RightOperand );
  }

  static function ge( abstracts\ValueExpression $LeftOperand, abstracts\ValueExpression $RightOperand )
  {
    return new GreaterEquals( $LeftOperand, $RightOperand );
  }

  static function eq( abstracts\ValueExpression $LeftOperand, abstracts\ValueExpression $RightOperand )
  {
    return new Equals( $LeftOperand, $RightOperand );
  }

  static function ne( abstracts\BooleanExpression $BooleanExpression )
  {
    return new Not( $BooleanExpression );
  }

  static function lt( abstracts\ValueExpression $LeftOperand, abstracts\ValueExpression $RightOperand )
  {
    return new Less( $LeftOperand, $RightOperand );
  }

  static function le( abstracts\ValueExpression $LeftOperand, abstracts\ValueExpression $RightOperand )
  {
    return new LessEquals( $LeftOperand, $RightOperand );
  }
}

<?php

namespace qck\exp;

/**
 *
 * @author muellerm
 */
abstract class Expression
{

  static function and_( array $Expressions = [] )
  {
    return new And_( $Expressions );
  }

  static function strlen( AtomicExpression $Param )
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

  static function gt( AtomicExpression $LeftOperand, AtomicExpression $RightOperand )
  {
    return new Greater( $LeftOperand, $RightOperand );
  }

  static function ge( AtomicExpression $LeftOperand, AtomicExpression $RightOperand )
  {
    return new GreaterEquals( $LeftOperand, $RightOperand );
  }

  static function eq( AtomicExpression $LeftOperand, AtomicExpression $RightOperand )
  {
    return new Equals( $LeftOperand, $RightOperand );
  }

  static function ne( BooleanExpression $BooleanExpression )
  {
    return new Not( $BooleanExpression );
  }

  static function lt( AtomicExpression $LeftOperand, AtomicExpression $RightOperand )
  {
    return new Less( $LeftOperand, $RightOperand );
  }

  static function le( AtomicExpression $LeftOperand, AtomicExpression $RightOperand )
  {
    return new LessEquals( $LeftOperand, $RightOperand );
  }

  abstract function evaluate( array $Data );
}

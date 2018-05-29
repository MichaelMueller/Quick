<?php

namespace qck\GraphStorage\Sql\Expressions;

/**
 *
 * @author muellerm
 */
abstract class Expression
{

  abstract function evaluate( array $Data, &$FilteredArray = [], &$FailedExpressions = [] );

  abstract function toSql( \qck\GraphStorage\Sql\Dictionary $Dictionary, array &$Params = [] );

  function filterVar( array $Data, &$FailedExpressions = [] )
  {
    $FilteredArray = [];
    $IsValid = $this->evaluate( $Data, $FilteredArray, $FailedExpressions );
    return $IsValid ? $FilteredArray : false;
  }

  static function and_( array $Expressions = [], $EvaluateAll = false )
  {
    return new And_( $Expressions, $EvaluateAll );
  }

  static function strlen( ValueExpression $Param )
  {
    return new Strlen( $Param );
  }

  static function id( $Name, $UseForFilteredArray = true )
  {
    return new Identifier( $Name, $UseForFilteredArray );
  }

  static function val( $Value )
  {
    return new Value( $Value );
  }

  static function gt( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new Greater( $LeftOperand, $RightOperand );
  }

  static function ge( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new GreaterEquals( $LeftOperand, $RightOperand );
  }

  static function eq( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new Equals( $LeftOperand, $RightOperand );
  }

  static function ne( BooleanExpression $BooleanExpression )
  {
    return new Not( $BooleanExpression );
  }

  static function lt( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new Less( $LeftOperand, $RightOperand );
  }

  static function le( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new LessEquals( $LeftOperand, $RightOperand );
  }
}

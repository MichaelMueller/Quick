<?php

namespace Qck\Expression\Interfaces;

/**
 * A Service Class for creating expressions
 * @author muellerm
 */
interface ExpressionFactory
{

  /**
   * @param bool $EvaluateAll
   * @return BooleanChain
   */
  function and_( $EvaluateAll = false );

  /**
   * @return BooleanChain
   */
  function or_();

  /**
   * 
   * @param \Qck\Expression\Interfaces\ValueExpression $Value
   * @param array $Choices
   * @return BooleanExpression
   */
  function choice( ValueExpression $Value, array $Choices );

  /**
   * @return SingleParamFunction
   */
  function strlen( ValueExpression $Param );

  /**
   * @return Var_
   */
  function var_( $Name, $FilterOut = false );

  /**
   * @return ValueExpression
   */
  function val( $Value );

  /**
   * @return BooleanExpression
   */
  function boolVal( $BoolValue );

  /**
   * @return Comparison
   */
  function gt( ValueExpression $LeftOperand, ValueExpression $RightOperand );

  /**
   * @return Comparison
   */
  function ge( ValueExpression $LeftOperand, ValueExpression $RightOperand );

  /**
   * @return Comparison
   */
  function eq( ValueExpression $LeftOperand, ValueExpression $RightOperand );

  /**
   * @return Comparison
   */
  function ne( BooleanExpression $BooleanExpression );

  /**
   * @return Comparison
   */
  function lt( ValueExpression $LeftOperand, ValueExpression $RightOperand );

  /**
   * @return Comparison
   */
  function le( ValueExpression $LeftOperand, ValueExpression $RightOperand );
}

<?php

namespace Qck\Interfaces\Expressions;

/**
 * A Service Class for creating expressions
 * @author muellerm
 */
interface ExpressionFactory
{

  /**
   * @return ExpressionFactory
   */
  function check( $VarName, $FilterOut = false );

  /**
   * @param mixed $Value A value to compare.
   * @param bool $ValueIsVarName if true the value will be treated as string varname
   * @return Comparison
   */
  function isEquals( $Value, $ValueIsVarName = false, $FilterOut = false );

  /**
   * @param mixed $Value A value to compare.
   * @param bool $ValueIsVarName if true the value will be treated as string varname
   * @return Comparison
   */
  function isNotEquals( $Value, $ValueIsVarName = false, $FilterOut = false );

  /**
   * @param mixed $Value A value to compare.
   * @param bool $ValueIsVarName if true the value will be treated as string varname
   * @return Comparison
   */
  function isGreater( $Value, $ValueIsVarName = false, $FilterOut = false );

  /**
   * @param mixed $Value A value to compare.
   * @param bool $ValueIsVarName if true the value will be treated as string varname
   * @return Comparison
   */
  function isGreaterOrEquals( $Value, $ValueIsVarName = false, $FilterOut = false );

  /**
   * @param mixed $Value A value to compare.
   * @param bool $ValueIsVarName if true the value will be treated as string varname
   * @return Comparison
   */
  function isLess( $Value, $ValueIsVarName = false, $FilterOut = false );

  /**
   * @param mixed $Value A value to compare.
   * @param bool $ValueIsVarName if true the value will be treated as string varname
   * @return Comparison
   */
  function isLessOrEquals( $Value, $ValueIsVarName = false, $FilterOut = false );

  /**
   * @param array $Expressions
   * @param bool $EvaluateAll
   * @return BooleanChain
   */
  function createAnd( array $Expressions = [], $EvaluateAll = false );

  /**
   * @param array $Expressions
   * @return BooleanChain
   */
  function createOr( array $Expressions = [] );

  /**
   * @param string $VarName
   * @return SingleParamFunction
   */
  function createStrlen( $VarName, $FilterOut = false );

  /**
   * @param \Qck\Interfaces\Sql\Table[] $Tables
   * @return \Qck\Expression\BooleanExpression
   */
  function createJoin( array $Tables );
}

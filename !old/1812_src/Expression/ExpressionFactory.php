<?php

namespace Qck\Expression;

use Qck\Interfaces\Expressions\ValueExpression;
use Qck\Interfaces\Expressions\BooleanExpression;

/**
 *
 * @author muellerm
 */
class ExpressionFactory implements \Qck\Interfaces\ExpressionsFactory
{

  function and_( $EvaluateAll = false )
  {
    return new \Qck\Expression\And_( $EvaluateAll );
  }

  function strlen( ValueExpression $Param )
  {
    return new \Qck\Expression\Strlen( $Param );
  }

  public function var_( $Name, $FilterOut = false )
  {
    return new Var_( $Name, $FilterOut );
  }

  function val( $Value )
  {
    return new \Qck\Expression\Value( $Value );
  }

  function gt( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expression\Greater( $LeftOperand, $RightOperand );
  }

  function ge( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expression\GreaterEquals( $LeftOperand, $RightOperand );
  }

  function eq( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expression\Equals( $LeftOperand, $RightOperand );
  }

  function ne( BooleanExpression $BooleanExpression )
  {
    return new \Qck\Expression\Not( $BooleanExpression );
  }

  function lt( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expression\Less( $LeftOperand, $RightOperand );
  }

  function le( ValueExpression $LeftOperand, ValueExpression $RightOperand )
  {
    return new \Qck\Expression\LessEquals( $LeftOperand, $RightOperand );
  }

  public function or_()
  {
    return new \Qck\Expression\Or_();
  }

  public function boolVal( $BoolValue )
  {
    return new BooleanValueExpression( $BoolValue );
  }

  public function choice( Interfaces\ValueExpression $Value, array $Choices )
  {
    $Or = $this->or_();
    foreach ( $Choices as $Choice )
      $Or->add( $this->eq( $Value, $this->val( $Choice ) ) );
    return $Or;
  }

  public function varEq( $varName, $Value )
  {
    return $this->eq( $this->var_( $varName ), $this->val( $Value ) );
  }

  public function varGreater( $varName, $Value )
  {
    return $this->gt( $this->var_( $varName ), $this->val( $Value ) );
  }

  public function varGt( $varName, $Value )
  {
    return $this->ge( $this->var_( $varName ), $this->val( $Value ) );
  }

  public function varLe( $varName, $Value )
  {
    return $this->le( $this->var_( $varName ), $this->val( $Value ) );
  }

  public function varLt( $varName, $Value )
  {
    return $this->lt( $this->var_( $varName ), $this->val( $Value ) );
  }

  public function varNe( $varName, $Value )
  {
    return $this->ne( $this->var_( $varName ), $this->val( $Value ) );
  }

  public function varEqVar( $varName, $var2Name )
  {
    return $this->eq( $this->var_( $varName ), $this->var_( $var2Name ) );
  }

  public function check( $VarName, $FilterOut = false )
  {
    $this->CurrentVarName = is_scalar( $VarName ) ? new Var_( $VarName, $FilterOut ) : $VarName;
    return $this;
  }

  public function createAnd( array $Expressions = array (), $EvaluateAll = false )
  {
    return new And_( $Expressions, $EvaluateAll );
  }

  public function createJoin( $Tables )
  {
    $And = $this->createAnd();
    /* @var $Tables \Qck\Interfaces\Sql\Table[] */
    foreach ( $Tables as $Table )
    {
      $ForeignKeyColumns = $Table->getForeignKeyCols();
      $TableName = $Table->getName();
      foreach ( $ForeignKeyColumns as $ForeignKeyColumn )
      {
        $RefTable = $ForeignKeyColumn->getRefTable();
        if ( in_array( $RefTable, $Tables ) )
        {
          $ForeignKeyName = $ForeignKeyColumn->getName();
          $RefTableName = $RefTable->getName();
          $RefTablePrimaryKeyName = $RefTable->getPrimaryKeyColumn()->getName();
          $And->add( $this->check( $TableName . "." . $ForeignKeyName )->isEquals( $RefTableName . "." . $RefTablePrimaryKeyName, true ) );
        }
      }
    }
    return $And;
  }

  public function createOr( array $Expressions = array () )
  {
    return new And_( $Expressions );
  }

  public function createStrlen( $VarName, $FilterOut = false )
  {
    return new Strlen( new Var_( $VarName, $FilterOut ) );
  }

  public function isEquals( $Value, $ValueIsVarName = false, $FilterOut = false )
  {
    return new Equals( $this->CurrentVarName, $ValueIsVarName ? new Var_( $Value, $FilterOut ) : new Value( $Value ) );
  }

  public function isGreater( $Value, $ValueIsVarName = false, $FilterOut = false )
  {
    return new Greater( $this->CurrentVarName, $ValueIsVarName ? new Var_( $Value, $FilterOut ) : new Value( $Value ) );
  }

  public function isGreaterOrEquals( $Value, $ValueIsVarName = false, $FilterOut = false )
  {
    return new GreaterEquals( $this->CurrentVarName, $ValueIsVarName ? new Var_( $Value, $FilterOut ) : new Value( $Value ) );
  }

  public function isLess( $Value, $ValueIsVarName = false, $FilterOut = false )
  {
    return new Less( $this->CurrentVarName, $ValueIsVarName ? new Var_( $Value, $FilterOut ) : new Value( $Value ) );
  }

  public function isLessOrEquals( $Value, $ValueIsVarName = false, $FilterOut = false )
  {
    return new LessEquals( $this->CurrentVarName, $ValueIsVarName ? new Var_( $Value, $FilterOut ) : new Value( $Value ) );
  }

  public function isNotEquals( $Value, $ValueIsVarName = false, $FilterOut = false )
  {
    return new NotEquals( $this->CurrentVarName, $ValueIsVarName ? new Var_( $Value, $FilterOut ) : new Value( $Value ) );
  }

  protected $CurrentVarName;

}

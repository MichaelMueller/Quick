<?php

namespace Qck\Expression\Tests;

use Qck\Expression\GreaterEquals;
use Qck\Expression\Strlen;
use Qck\Expression\Var_;
use Qck\Expression\Value;
use Qck\Expression\Equals;
use Qck\Expression\And_;

/**
 *
 * @author muellerm
 */
class ExpressionTest implements \Qck\Interfaces\Test
{

  public function getRequiredTests()
  {
    return array ();
  }

  public function exec( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    /* @var $Expr \Qck\Interfaces\Expressions\ExpressionFactory */
    $Expr = $ServiceRepo->get( \Qck\Interfaces\Expressions\ExpressionFactory::class );

    $Data = [];
    $Data[ "Name" ] = "Michi";
    $Data[ "Pw" ] = "12345";
    $Data[ "PwConfirm" ] = "12345";
    $Data[ "Age" ] = 18;
    $Data[ "Submit" ] = "OK";

    $TargetSql = "(strlen ( Name )  >= ? and Pw = PwConfirm and strlen ( Pw )  >= ? and Age >= ?)";
    // validation expression: strlen(Name) > 4 && strlen(Pw) > 3 && Pw == PwConfirm && Age >= 18
    $NameValidator = $Expr->check( $Expr->createStrlen( "Name" ) )->isGreaterOrEquals( 4 );
    $PwValidator = $Expr->check( $Expr->createStrlen( "Pw" ) )->isGreaterOrEquals( 3 );
    $PwEqualsValidator = $Expr->check( "Pw" )->isEquals( "PwConfirm", true, true );
    $AgeValidator = $Expr->check( "Age" )->isGreaterOrEquals( 18 );
    $CompleteValidator = $Expr->createAnd( [ $NameValidator, $PwEqualsValidator, $PwValidator, $AgeValidator ], true );

    if ( !$CompleteValidator->evaluate( $Data ) )
      throw new \Exception( "CompleteValidator failed" );

    $Filtered = $CompleteValidator->filterVar( $Data );
    if ( count( $Filtered ) != 3 || isset( $Filtered[ "Submit" ] ) )
      throw new \Exception( "filterVar failed" );

    $Params = [];
    $Sql = $CompleteValidator->toSql( new DbDialect(), $Params );

    if ( $Sql != $TargetSql || $Params != [ 4, 3, 18 ] )
      throw new \Exception( "toSql failed" );
  }

  protected $Dir;

}

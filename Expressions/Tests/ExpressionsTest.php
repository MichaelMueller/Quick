<?php

namespace qck\Expressions\Tests;

use qck\Expressions\Abstracts\Expression as ex;

/**
 *
 * @author muellerm
 */
class ExpressionsTest extends \qck\core\abstracts\Test
{

  public function run( \qck\core\interfaces\AppConfig $config )
  {
    $TrueRegistrationData = [];
    $TrueRegistrationData[ "Name" ] = "Michi";
    $TrueRegistrationData[ "Pw" ] = "12345";
    $TrueRegistrationData[ "PwConfirm" ] = "12345";
    $TrueRegistrationData[ "Age" ] = 18;
    $TrueRegistrationData[ "Submit" ] = "OK";

    // validation expression: strlen(Name) > 4 && strlen(Pw) > 3 && Pw == PwConfirm && Age >= 18
    $NameValidator = ex::gt( ex::strlen( ex::id( "Name" ) ), ex::val( 4 ) );
    $PwValidator = ex::gt( ex::strlen( ex::id( "Pw" ) ), ex::val( 3 ) );
    $PwEqualsValidator = ex::eq( ex::id( "Pw" ), ex::id( "PwConfirm", false ) );
    $AgeValidator = ex::ge( ex::id( "Age" ), ex::val( 18 ) );
    $Complete = ex::and_( [ $NameValidator, $PwEqualsValidator, $PwValidator, $AgeValidator ] );
    $this->assert( $Complete->evaluate( $TrueRegistrationData ) );
    $Filtered = $Complete->filterVar( $TrueRegistrationData );
    $this->assert( count( $Filtered ) == 3 && isset( $Filtered[ "Submit" ] ) == false );
  }

  public function getRequiredTests()
  {
    return array ();
  }

  protected $Dir;

}

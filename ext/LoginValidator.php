<?php

namespace qck\ext;

/**
 *
 * @author muellerm
 */
class LoginValidator extends abstracts\Validator
{

  function __construct( interfaces\Authenticator $Authenticator, interfaces\Session $Session )
  {
    $this->Session = $Session;
    $this->Authenticator = $Authenticator;
  }

  protected function validateImpl()
  {
    if ( $this->Authenticator->check( $this->at( 0 ), $this->at( 1 ) ) )
      $this->Session->setUsername( $this->at( 0 ) );
    else
      $this->addError( "Invalid Credentials" );
  }

  /**
   * @var interfaces\Session
   */
  protected $Session;

  /**
   * @var interfaces\Authenticator
   */
  protected $Authenticator;

}

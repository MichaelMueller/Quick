<?php

namespace qck\ext;

/**
 *
 * @author muellerm
 */
class Authenticator implements interfaces\Authenticator
{

  function __construct( interfaces\UserDb $UserDb,
                        interfaces\AuthenticatorFactory $AuthenticatorFactory,
                        interfaces\PasswordHasher $PasswordHasher )
  {
    $this->UserDb = $UserDb;
    $this->AuthenticatorFactory = $AuthenticatorFactory;
    $this->PasswordHasher = $PasswordHasher;
  }

  public function check( $Username, $PlainTextPassword )
  {
    $User = $this->UserDb->getUser( $Username );
    if ( !$User )
      return false;
    // Use a custom Authenticator?
    $CustomAuthenticator = $this->AuthenticatorFactory->create( $User->getAuthenticatorName() );
    if ( $CustomAuthenticator )
      return $CustomAuthenticator->check( $Username, $PlainTextPassword );
    else
    {
      return $this->PasswordHasher->verify( $PlainTextPassword, $User->getHashedPassword() ) === true;
    }
  }

  /**
   *
   * @var interfaces\UserDb
   */
  protected $UserDb;

  /**
   *
   * @var interfaces\AuthenticatorFactory
   */
  protected $AuthenticatorFactory;

  /**
   *
   * @var interfaces\PasswordHasher
   */
  protected $PasswordHasher;

}

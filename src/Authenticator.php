<?php

namespace Qck\Ext;

/**
 *
 * @author muellerm
 */
class Authenticator implements \Qck\Interfaces\Authenticator
{

  function __construct( \Qck\Interfaces\UserDb $UserDb,
                        \Qck\Interfaces\PasswordHasher $PasswordHasher )
  {
    $this->UserDb = $UserDb;
    $this->PasswordHasher = $PasswordHasher;
  }

  public function check( $Username, $PlainTextPassword )
  {
    $User = $this->UserDb->getUser( $Username );
    if ( !$User )
      return false;
    // Use a custom Authenticator?
    $AuthenticatorFqcn = $User->getAuthenticatorFqcn();
    $Authenticator = new $AuthenticatorFqcn();
    return $Authenticator->check( $Username, $PlainTextPassword );
  }

  /**
   *
   * @var \Qck\Interfaces\UserDb
   */
  protected $UserDb;

  /**
   *
   * @var \Qck\Interfaces\PasswordHasher
   */
  protected $PasswordHasher;

}

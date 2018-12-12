<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class DefaultAuthenticator implements \Qck\Interfaces\Authenticator
{

  function __construct( \Qck\Interfaces\UserDb $UserDb, \Qck\Interfaces\PasswordHasher $PasswordHasher, \Qck\Interfaces\Session $Session )
  {
    $this->UserDb         = $UserDb;
    $this->PasswordHasher = $PasswordHasher;
    $this->Session        = $Session;
  }

  public function check( $Username, $PlainTextPassword )
  {
    $CredentialsOk = false;
    $User          = $this->UserDb->getUser( $Username );

    if ( $User )
    {
      // Use a custom Authenticator?
      $Authenticator = $User->getAuthenticator();

      if ( $Authenticator )
        $CredentialsOk = $Authenticator->check( $Username, $PlainTextPassword );
      else
        $CredentialsOk = $this->PasswordHasher->verify( $PlainTextPassword, $User->getHashedPassword() );
    }
    if ( $CredentialsOk )
    {
      $this->Session->setUsername( $Username );
    }

    return $CredentialsOk;
  }

  public function assertAuthenticatedUser( $RedirectUrl = null )
  {
    if ( $this->Session->getUsername() === null )
    {
      if ( $RedirectUrl )
      {
        header( "Location: " . $RedirectUrl );
        exit();
      }
      else
      {
        throw new Exception( "User not authenticated", Interfaces\Response::EXIT_CODE_UNAUTHORIZED );
      }
    }
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

  /**
   *
   * @var \Qck\Interfaces\Session
   */
  protected $Session;

}

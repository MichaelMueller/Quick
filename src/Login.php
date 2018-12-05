<?php

namespace Qck\Ext;

/**
 *
 * @author muellerm
 */
class Login implements \Qck\Interfaces\Authenticator
{

  function __construct(\Qck\Interfaces\UserDb $UserDb, \Qck\Interfaces\PasswordHasher $PasswordHasher, \Qck\Interfaces\Session $Session)
  {
    $this->UserDb         = $UserDb;
    $this->PasswordHasher = $PasswordHasher;
    $this->Session        = $Session;
  }

  public function check($Username, $PlainTextPassword)
  {
    $User = $this->UserDb->getUser($Username);
    if ($User)
    {
      // Use a custom Authenticator?
      $Authenticator = $User->getAuthenticator();
      $CredentialsOk = false;
      if ($Authenticator)
        $CredentialsOk = $Authenticator->check($Username, $PlainTextPassword);
      else
        $CredentialsOk = $this->PasswordHasher->verify($PlainTextPassword, $User->getHashedPassword());
    }
    return false;
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

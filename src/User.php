<?php

namespace Qck;

/**
 *
 * @property string $Username
 * @property string $HashedPassword
 * @property Interfaces\Authenticator  $Authenticator
 * @property bool $Admin
 * @author muellerm
 */
class User extends PersistableObject implements \Qck\Interfaces\User, Interfaces\PersistableObject
{

  public function getAuthenticator()
  {
    return $this->Authenticator;
  }

  public function getHashedPassword()
  {
    return $this->HashedPassword;
  }

  public function getUsername()
  {
    return $this->Username;
  }

  public function isAdmin()
  {
    return $this->Admin;
  }

}

<?php

namespace Qck\Apps\DatabaseApp;

/**
 *
 * @author muellerm
 * @property string $Username
 * @property bool $Admin
 * @property string $AuthenticatorFqcn
 * @property string $HashedPassword
 */
class User extends \stdClass implements \Qck\Interfaces\User
{

  function __construct()
  {
    $this->Username = null;
    $this->Admin = false;
    $this->HashedPassword = null;
    $this->AuthenticatorFqcn = UserDb::class;
  }

  public function getAuthenticatorFqcn()
  {
    return $this->AuthenticatorFqcn;
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

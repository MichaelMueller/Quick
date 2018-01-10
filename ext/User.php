<?php

namespace qck\ext;

/**
 *
 * @author muellerm
 * @property string $Username
 * @property string $HashedPassword a hashed password
 * @property bool $Admin
 * @property string $AuthenticatorName
 */
class User extends \stdClass implements interfaces\User
{

  function __construct( $Username = null, $HashedPassword = null, $Admin = null,
                        $AuthenticatorName = null )
  {
    $this->Username = $Username;
    $this->HashedPassword = $HashedPassword;
    $this->Admin = $Admin;
    $this->AuthenticatorName = $AuthenticatorName;
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

  public function getAuthenticatorName()
  {
    return $this->AuthenticatorName;
  }
}

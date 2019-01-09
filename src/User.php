<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class User implements \Qck\Interfaces\User, Interfaces\Serialization\Serializable
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

  public function fromScalarArray( array $ScalarArray,
                                   Interfaces\Serialization\Source $Source,
                                   $Reload = false )
  {
    $this->Username       = $ScalarArray[ 0 ];
    $this->HashedPassword = $ScalarArray[ 1 ];
    $this->Authenticator  = $ScalarArray[ 2 ];
    $this->Admin          = $ScalarArray[ 3 ];
  }

  public function getOwnedObjects()
  {
    return [];
  }

  public function toScalarArray( Interfaces\Serialization\ObjectIdProvider $ObjectIdProvider )
  {
    return [ $this->Username, $this->HashedPassword, $this->Authenticator, $this->Admin ];
  }

  protected $Username;
  protected $HashedPassword;
  protected $Authenticator;
  protected $Admin;

}

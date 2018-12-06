<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class User implements \Qck\Interfaces\User
{

  function getName()
  {
    return $this->Name;
  }

  function getHashedPassword()
  {
    return $this->HashedPassword;
  }

  function getAuthenticator()
  {
    return $this->Authenticator;
  }

  function setName( $Name )
  {
    $this->Name = $Name;
  }

  function setHashedPassword( $HashedPassword )
  {
    $this->HashedPassword = $HashedPassword;
  }

  function setAuthenticator( $Authenticator )
  {
    $this->Authenticator = $Authenticator;
  }

  protected $Name;
  protected $HashedPassword;
  protected $Authenticator;

}

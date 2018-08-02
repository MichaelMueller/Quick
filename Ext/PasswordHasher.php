<?php

namespace Qck\Ext;

/**
 *
 * @author muellerm
 */
class PasswordHasher implements \Qck\Interfaces\PasswordHasher
{

  function verify( $PlainTextPassword, $HashedPassword )
  {
    return password_verify( $PlainTextPassword, $HashedPassword ) === true;
  }

  function createHash( $plainTextPassword )
  {
    return password_hash( $plainTextPassword, PASSWORD_DEFAULT );
  }
}

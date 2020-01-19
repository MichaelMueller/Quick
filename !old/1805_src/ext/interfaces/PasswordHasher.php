<?php

namespace qck\ext\interfaces;

/**
 * represents a user of the system (which gets authenticated in a sense)
 */
interface PasswordHasher
{

  /**
   * 
   * @param type $PlainTextPassword
   * @return string a hashed password
   */
  function createHash( $PlainTextPassword );

  /**
   * 
   * @param bool
   */
  function verify( $PlainTextPassword, $HashedPassword );
}

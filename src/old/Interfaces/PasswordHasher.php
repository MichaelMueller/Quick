<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface PasswordHasher
{

  function verify($PlainTextPassword, $HashedPassword);

  function createHash($plainTextPassword);
}

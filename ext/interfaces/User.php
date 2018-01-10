<?php
namespace qck\ext\interfaces;

/**
* represents a user of the system (which gets authenticated in a sense)
*/
interface User
{  
  /**
   * @return string the username
   */
  function getUsername();
  /**
   * @return string the password. Attention: Might be null if password is stored elsewhere
   */
  function getHashedPassword();
  /**
   * @return bool true if superadmin, false otherwise
   */
  function isAdmin();
  /**
   * @return string a custom authenticator name or null if none
   */
  function getAuthenticatorName();
}
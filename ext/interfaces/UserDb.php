<?php
namespace qck\ext\interfaces;

/**
* represents a user of the system (which gets authenticated in a sense)
*/
interface UserDb
{
  /**
   * 
   * @param string $username
   * @return User Get the user or null
   */
  function getUser($username);
  
}
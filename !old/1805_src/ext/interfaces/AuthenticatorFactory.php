<?php

namespace qck\ext\interfaces;

/**
 * represents a user of the system (which gets authenticated in a sense)
 */
interface AuthenticatorFactory
{

  /**
   * 
   * @param type $AuthenticatorName
   * @return Authenticator an Authenticator for this name, if not found, will return the DefaultAuthenticator
   */
  function create( $AuthenticatorName );

  /**
   * @return array Description
   */
  function getAuthenticatorList();
}

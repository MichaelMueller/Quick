<?php

namespace qck\ext;

/**
 *
 * @author muellerm
 */
class AuthenticatorFactory implements interfaces\AuthenticatorFactory
{

  function __construct( array $Authenticators = array () )
  {
    $this->Authenticators = $Authenticators;
  }

  public function create( $AuthenticatorName )
  {

    $Fqcn = isset( $this->Authenticators[ $AuthenticatorName ] ) ? $this->Authenticators[ $AuthenticatorName ] : null;
    return $Fqcn ? new $Fqcn() : null;
  }

  public function getAuthenticatorList()
  {
    return array_keys( $this->Authenticators );
  }

  /**
   *
   * @var array
   */
  protected $Authenticators = [];

}

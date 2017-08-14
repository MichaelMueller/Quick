<?php

namespace qck\abstracts;

/**
 * a Controller that only runs for logged in users
 *
 * @author muellerm
 */
abstract class AuthenticatedUserController extends Controller
{

  function run( \qck\interfaces\AppConfig $config )
  {
    $this->AppConfig = $config;
    $this->assertLoggedIn();
    return $this->proxyRun();
  }

  /**
   * 
   * @return \qck\interfaces\User
   */
  protected function getCurrentUser()
  {
    if ( is_null( $this->User ) )
      $this->User = $this->AppConfig->getSession()->getUser();
    return $this->User;
  }

  protected function setCurrentUser( \qck\interfaces\User $User )
  {
    $this->AppConfig->getSession()->setUser( $User );
  }

  protected function assertLoggedIn()
  {
    if ( $this->getCurrentUser() == null )
    {
      $this->throwUnauthorized();
    }
  }

  protected function throwUnauthorized()
  {
    throw new Exception( "Cannot execute Controller " . get_class( $this ) . ". Requesting client is not logged in", \qck\interfaces\Response::CODE_UNAUTHORIZED );
  }

  /**
   *
   * @var \qck\interfaces\User
   */
  private $User;

}

<?php

namespace qck\ext\abstracts;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class Controller extends \qck\core\abstracts\Controller
{

  protected function assertAuthenticated()
  {
    if ( is_null( $this->getUsername() ) )
      $this->throwUnauthorized();
  }

  protected function assertAdminLoggedIn( \qck\ext\interfaces\UserDb $UserDb)
  {
    $User = $UserDb->getUser( $this->getUsername() );
    if ( $User == null || $User->isAdmin() !== true )
      $this->throwUnauthorized();
  }
  
  function getUsername()
  {
    return $this->getAppConfig()->getSession()->getUsername();
  }

  protected function throwUnauthorized()
  {
    throw new \Exception( "Cannot execute Controller " . get_class( $this ) . ". Requesting client is not logged in", \qck\core\interfaces\Response::CODE_UNAUTHORIZED );
  }
}

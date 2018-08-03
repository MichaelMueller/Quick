<?php

namespace Qck\Ext;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class ExtController extends \Qck\Core\CoreController
{

  /**
   * @return AppConfig
   */
  function getAppConfig()
  {
    return $this->AppConfig;
  }

  protected function assertAuthenticated()
  {
    if ( is_null( $this->getUsername() ) )
      $this->throwUnauthorized();
  }

  protected function assertAdminLoggedIn( \Qck\Interfaces\UserDb $UserDb )
  {
    $User = $UserDb->getUser( $this->getUsername() );
    if ( $User == null || $User->isAdmin() !== true )
      $this->throwUnauthorized();
  }

  function isAuthenticated()
  {
    return $this->getUsername() !== null;
  }

  function getUsername()
  {
    return $this->getAppConfig()->getSession()->getUsername();
  }

  protected function throwUnauthorized()
  {
    throw new \Exception( "Cannot execute Controller " . get_class( $this ) . ". Requesting client is not logged in", \Qck\Interfaces\Response::CODE_UNAUTHORIZED );
  }
}

<?php

namespace qck\abstracts;

/**
 * a Controller that only runs for logged in users
 *
 * @author muellerm
 */
abstract class AdminController extends Controller 
{
  
  function run( \qck\interfaces\AppConfig $config )
  {
    $this->AppConfig = $config;
    $this->assertAdminLoggedIn();
    return $this->proxyRun();
  }

  protected function assertAdminLoggedIn()
  {
    $User = $this->getCurrentUser();
    if ( $User == null || $User->isAdmin() === true )
    {
      $this->throwUnauthorized();
    }
  }
}

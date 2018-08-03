<?php

namespace Qck\Apps\DatabaseApp;

/**
 * Description of HelloWorldPage
 *
 * @author muellerm
 */
class AppConfig extends \Qck\Ext\AppConfig
{

  public function getAppName()
  {
    return "Qck.Apps.DatabaseApp";
  }

  public function getRouter()
  {
    return $this->getSingleton( "ClientInfo", function()
        {
          return new \Qck\Core\Router( "\\Qck\\Apps\\DatabaseApp\\Controller", $this->getInputs() );
        } );
  }
}

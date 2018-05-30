<?php

namespace qck\apps\helloworld;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
class AppConfig extends \qck\core\abstracts\AppConfig
{

  public function getControllerFactory()
  {
    return $this->getSingleton( "ControllerFactory", function()
        {
          return new \qck\core\ControllerFactory( "\\qck\\apps\\helloworld\\controller" );
        } );
  }

  public function getAppName()
  {
    return "qck\apps\helloworld";
  }
}

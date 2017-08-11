<?php

namespace qck\apps\helloworld;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class AppConfigFactory implements \qck\interfaces\AppConfigFactory
{

  public function create()
  {
    if ( file_exists( __DIR__ . DIRECTORY_SEPARATOR . "AppConfigLocal.php" ) )
    {
      return new AppConfigLocal();
    }
    else
    {
      return new AppConfig();
    }
  }

}

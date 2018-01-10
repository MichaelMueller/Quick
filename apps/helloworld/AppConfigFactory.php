<?php

namespace qck\apps\helloworld;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class AppConfigFactory implements \qck\core\interfaces\AppConfigFactory
{

  public function create()
  {
    return file_exists( "AppConfigLocal.php" ) ? new AppConfigLocal() : new AppConfig();
  }


}

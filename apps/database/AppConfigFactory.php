<?php

namespace qck\apps\database;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class AppConfigFactory implements \qck\interfaces\AppConfigFactory
{
  /*
   * @return AppConfig an AppConfig Object
   */
  public function create()
  {
    if($this->AppConfig)
      return $this->AppConfig;
    
    if ( file_exists( __DIR__ . DIRECTORY_SEPARATOR . "AppConfigLocal.php" ) )
    {
      $this->AppConfig = new AppConfigLocal();
    }
    else
    {
      $this->AppConfig = new AppConfig();
    }
    return $this->create();
  }
  
  protected $AppConfig;
}

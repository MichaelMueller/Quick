<?php

namespace Qck;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class AppConfigFactory implements \Qck\Interfaces\AppConfigFactory
{

  function __construct( $AppConfigFqcn, $LocalConfigPath )
  {
    $this->AppConfigFqcn = $AppConfigFqcn;
    $this->LocalConfigPath = $LocalConfigPath;
  }

  /**
   * 
   * @return AppConfig
   */
  public function create()
  {
    $LocalConfigName = $this->LocalConfigPath . "/AppConfig.php";
    if ( file_exists( $LocalConfigName ) )
    {
      require_once $LocalConfigName;
      return new \AppConfig();
    }
    else
      return new $this->AppConfigFqcn();
  }

  /**
   *
   * @var string
   */
  protected $AppConfigFqcn;

  /**
   *
   * @var string
   */
  protected $LocalConfigPath;

  /**
   *
   * @var Env
   */
  protected $Env;

}

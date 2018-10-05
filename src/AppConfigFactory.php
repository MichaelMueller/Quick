<?php

namespace Qck;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class AppConfigFactory implements \Qck\Interfaces\AppConfigFactory
{

  function __construct( $DefaultAppConfigFqcn, $WorkingDir, Env $Env )
  {
    $this->DefaultAppConfigFqcn = $DefaultAppConfigFqcn;
    $this->WorkingDir = $WorkingDir;
    $this->Env = $Env;
  }

  /**
   * 
   * @return AppConfig
   */
  public function create()
  {
    $LocalConfigName = $this->WorkingDir . $this->Env . "/AppConfig.php";
    if ( file_exists( $LocalConfigName ) )
    {
      require_once $LocalConfigName;
      return new \AppConfig();
    }
    else
      return new $this->DefaultAppConfigFqcn();
  }

  /**
   *
   * @var string
   */
  protected $DefaultAppConfigFqcn;

  /**
   *
   * @var string
   */
  protected $WorkingDir;
  
  /**
   *
   * @var string
   */
  protected $LocalConfigClassName="AppConfig";

  /**
   *
   * @var Env
   */
  protected $Env;

}

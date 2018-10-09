<?php

namespace Qck;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class AppFactory implements \Qck\Interfaces\AppFactory
{

  function __construct( $AppConfigFqcn,
                        Interfaces\LocalDataDirProvider $LocalDataDirProvider )
  {
    $this->AppConfigFqcn = $AppConfigFqcn;
    $this->LocalDataDirProvider = $LocalDataDirProvider;
  }

  /**
   * 
   * @return AppConfig
   */
  public function create()
  {
    $LocalConfigFilePath = $this->LocalDataDirProvider->getLocalDataDir( false ) . $this->LocalConfigFileName;
    if ( is_file( $LocalConfigFilePath ) )
    {
      require_once $LocalConfigFilePath;
      return new $this->LocalAppConfigFqcn;
    }
    else
      return new $this->AppConfigFqcn();
  }

  function setLocalConfigFileName( $LocalConfigFileName )
  {
    $this->LocalConfigFileName = $LocalConfigFileName;
  }

  /**
   *
   * @var string
   */
  protected $AppConfigFqcn;

  /**
   *
   * @var Interfaces\LocalDataDirProvider
   */
  protected $LocalDataDirProvider;

  /**
   *
   * @var Interfaces\LocalDataDirProvider
   */
  protected $LocalDataDirProvider;

  /**
   *
   * @var string
   */
  protected $LocalAppConfigFqcn = "\\AppConfig";

  /**
   *
   * @var string
   */
  protected $LocalConfigFileName = "AppConfig.php";

}

<?php

namespace qck\apps\testapp;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class AppConfigFactory implements \qck\core\interfaces\AppConfigFactory
{

  function __construct( $Argv = null )
  {
    $this->Argv = $Argv;
  }

  /**
   * 
   * @return AppConfig
   */
  public function create()
  {
    return new AppConfig( $this->Argv );
  }

  private $Argv;

}

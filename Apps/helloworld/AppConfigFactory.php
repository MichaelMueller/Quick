<?php

namespace Qck\Apps\HelloWorld;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
class AppConfigFactory implements \Qck\Interfaces\AppConfigFactory
{

  function __construct( $Argv )
  {
    $this->AppConfig = new AppConfig( $Argv );
  }

  public function create()
  {
    return $this->AppConfig;
  }

  protected $AppConfig;

}

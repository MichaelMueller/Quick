<?php

namespace Qck;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
class AppConfig implements \Qck\Interfaces\AppConfig
{

  function __construct( $AppName )
  {
    $this->AppName = $AppName;
  }

  function setWorkingDir( $WorkingDir )
  {
    $this->WorkingDir = $WorkingDir;
  }

  function setHostName( $HostName )
  {
    $this->HostName = $HostName;
  }

  function getWorkingDir( $createIfNotExists = true )
  {
    static $var = null;
    if ( !$var )
    {
      $var = $this->WorkingDir;
      if ( $createIfNotExists && !is_dir( $var ) )
        mkdir( $var );
    }
    return $var;
  }

  function getAppName()
  {
    return $this->AppName;
  }

  public function getHostName()
  {
    return $this->HostName ? $this->HostName : gethostname();
  }

  protected $AppName;
  protected $WorkingDir;
  protected $HostName;

}

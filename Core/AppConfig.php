<?php

namespace Qck\Core;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class AppConfig implements \Qck\Interfaces\AppConfig
{

  function __construct( $Argv )
  {
    $this->Argv = $Argv;
  }

  function isCli()
  {
    return isset( $_SERVER[ 'argc' ] );
  }

  public function getHostName()
  {
    return $this->getSingleton( "HostName", function()
        {
          return gethostname();
        } );
  }

  function getWorkingDir()
  {
    return null;
  }

  function getArgv()
  {
    return $this->Argv;
  }

  function getErrorController()
  {
    return null;
  }

  function getAdminMailer()
  {
    return null;
  }

  public function shouldPrintErrors()
  {
    return false;
  }

  protected function getSingleton( $key, callable $createFunction )
  {
    if ( !isset( $this->Singletons[ $key ] ) )
      $this->Singletons[ $key ] = $createFunction();

    return $this->Singletons[ $key ];
  }

  protected $Argv;
  private $Singletons = array ();

}

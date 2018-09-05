<?php

namespace Qck\Core;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class AppConfig implements \Qck\Interfaces\AppConfig
{

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

  public function getInputs()
  {
    return $this->getSingleton( "Inputs", function()
        {
          return new Inputs();
        } );
  }

  function getWorkingDir( $createIfExists = true )
  {
    return $this->getSingleton( "Inputs", function() use($createIfExists)
        {
          $Dir = "var";
          if ( $createIfExists && !is_dir( $Dir ) )
            mkdir( $Dir );
          return $Dir;
        } );
  }

  function getArgv()
  {
    return isset( $_SERVER[ 'argv' ] ) ? $_SERVER[ 'argv' ] : null;
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

  private $Singletons = array ();

}

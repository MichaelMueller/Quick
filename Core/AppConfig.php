<?php

namespace Qck\Core;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class AppConfig implements \Qck\Interfaces\AppConfig
{

  const DEFAULT_WORKING_DIR = "var";

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

  function getWorkingDir( $createIfNotExists = true )
  {
    return $this->getSingleton( "Inputs", function() use($createIfNotExists)
        {
          $Dir = self::DEFAULT_WORKING_DIR;
          if ( $createIfNotExists && !is_dir( $Dir ) )
            mkdir( $Dir );
          return $Dir;
        } );
  }

  function getWorkingSubDir( $subDir, $createIfNotExists = true )
  {
    $FullSubDir = $this->getWorkingDir( $createIfNotExists ) . DIRECTORY_SEPARATOR . $subDir;
    if ( $createIfNotExists && !is_dir( $FullSubDir ) )
      mkdir( $FullSubDir );
    return $FullSubDir;
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
  
  protected function getSingleton( $key, callable $createFunction )
  {
    if ( !isset( $this->Singletons[ $key ] ) )
      $this->Singletons[ $key ] = $createFunction();

    return $this->Singletons[ $key ];
  }

  private $Singletons = array ();

}

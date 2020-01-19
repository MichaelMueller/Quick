<?php

namespace qck\core\abstracts;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class AppConfig implements \qck\core\interfaces\AppConfig
{

  public function getHostInfo()
  {
    return $this->getSingleton( "HostInfo", function()
        {
          return gethostname();
        } );
  }

  function getWorkingDir()
  {
    return $this->getSingleton( "WorkingDir", function()
        {
          return dirname( (new \ReflectionClass( $this ) )->getFileName() ) . DIRECTORY_SEPARATOR . "var";
        } );
  }

  function getDataDir( $createIfNotExists = true )
  {
    return $this->getDirWithCheck( $this->getWorkingDir() . DIRECTORY_SEPARATOR . "data", $createIfNotExists );
  }

  function getDataSubDir( $subdir, $createIfNotExists = true )
  {
    return $this->getDirWithCheck( $this->getDataDir() . DIRECTORY_SEPARATOR . $subdir, $createIfNotExists );
  }

  function getCacheDir( $createIfNotExists = true )
  {
    return $this->getDirWithCheck( $this->getWorkingDir() . DIRECTORY_SEPARATOR . "cache", $createIfNotExists );
  }

  function getCacheSubDir( $subdir, $createIfNotExists = true )
  {
    return $this->getDirWithCheck( $this->getCacheDir() . DIRECTORY_SEPARATOR . $subdir, $createIfNotExists );
  }

  protected function getDirWithCheck( $dir, $createIfNotExists = true )
  {
    if ( $createIfNotExists && !file_exists( $dir ) )
      mkdir( $dir, 0777, true );
    return $dir;
  }

  function getErrorController()
  {
    return null;
  }

  function getAdminMailer()
  {
    return null;
  }

  function getTests()
  {
    return array ();
  }

  public function mkLink( $ControllerClassName, $args = array () )
  {
    $Link = "?" . $this->getControllerFactory()->getQueryKey() . "=" . $ControllerClassName;

    if ( is_array( $args ) )
    {
      foreach ( $args as $key => $value )
        $Link .= "&" . $key . "=" . (urlencode( $value ));
    }
    return $Link;
  }

  function getCurrentControllerName()
  {
    return $this->getControllerFactory()->getCurrentControllerClassName();
  }

  protected function getSingleton( $key, callable $createFunction )
  {
    if ( !isset( $this->Singletons[ $key ] ) )
      $this->Singletons[ $key ] = $createFunction();

    return $this->Singletons[ $key ];
  }

  private $Singletons = array ();

}

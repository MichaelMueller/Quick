<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class DefaultDirectoryConfig implements Interfaces\DirectoryConfig
{

  function __construct( $BaseDir )
  {
    $this->BaseDir = $BaseDir;
  }

  function getBaseDir()
  {
    return $this->BaseDir;
  }

  public function getDataDir( $Subdir = null, $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->join( $this->BaseDir . "/var/data", $Subdir ), $createIfNotExists );
  }

  public function getTmpDir( $Subdir = null, $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->join( $this->BaseDir . "/var/tmp", $Subdir ), $createIfNotExists );
  }

  public function getLocalDataDir( $Subdir = null, $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->join( $this->BaseDir . "/var/local/" . $this->getHostName(), $Subdir ), $createIfNotExists );
  }

  public function getAssetsDir( $Subdir = null, $createIfNotExists = false )
  {
    return $this->createIfNotExists( $this->join( $this->BaseDir . "/assets", $Subdir ), $createIfNotExists );
  }

  protected function createIfNotExists( $Dir, $createIfNotExists )
  {
    if ( $createIfNotExists && ! is_dir( $Dir ) )
      mkdir( $Dir, 0777, true );
    return $Dir;
  }

  protected function join( $BasePath, $FileName )
  {
    $Path = $BasePath . ($FileName !== null ? DIRECTORY_SEPARATOR . $FileName : "");

    return strpos( $Path, "\\" ) !== false ? str_replace( "/", "\\", $Path ) : str_replace( "\\", "/", $Path );
  }

  /**
   * @return Interfaces\Mail\AdminMailer
   */
  function getHostName()
  {
    if ( ! $this->HostName )
      $this->HostName = gethostname();
    return $this->HostName;
  }

  protected $BaseDir;
  protected $HostName;

}

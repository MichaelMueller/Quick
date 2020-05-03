<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class DefaultHostConfig implements Interfaces\HostConfig
{

  function __construct( $ProjectDir )
  {
    $this->ProjectDir = $ProjectDir;
  }

  function getProjectDir()
  {
    return $this->ProjectDir;
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

  public function getWorkingDir(  )
  {
    return $this->ProjectDir . "/var";
  }

  protected function createIfNotExists( $FilePath, $createIfNotExists, $IsDir )
  {
    if ( $createIfNotExists && ! file_exists( $FilePath ) )
    {
      if ( $IsDir )
        mkdir( $FilePath, 0777, true );
      else
      {
        $ParentDir = dirname( $FilePath );
        if ( ! is_dir( $ParentDir ) )
          mkdir( $ParentDir, 0777, true );
        touch( $FilePath );
      }
    }
    return $FilePath;
  }

  protected function join( $BasePath, $FileName )
  {
    $Path = $BasePath . ($FileName !== null ? DIRECTORY_SEPARATOR . $FileName : "");

    return strpos( $Path, "\\" ) !== false ? str_replace( "/", "\\", $Path ) : str_replace( "\\", "/", $Path );
  }

  protected $ProjectDir;
  protected $HostName;

}

<?php

namespace Qck;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class DirectoryConfig implements \Qck\Interfaces\DirectoryConfig, Interfaces\LocalDataDirProvider
{

  static public function getHostName()
  {
    static $var = null;
    if ( !$var )
      $var = gethostname();
    return $var;
  }

  function __construct( $WorkingDir )
  {
    $this->WorkingDir = $WorkingDir;
  }

  public function getDataDir( $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->WorkingDir . "/data", $createIfNotExists );
  }

  public function getLocalDataDir( $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->WorkingDir . "/" . self::getHostName(), $createIfNotExists );
  }

  public function getTmpDir( $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->WorkingDir . "/tmp", $createIfNotExists );
  }

  public function getTmpSubDir( $DirName, $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->getTmpDir( false ) . "/" . $DirName, $createIfNotExists );
  }

  protected function createIfNotExists( $Dir, $createIfNotExists )
  {
    if ( $createIfNotExists && !is_dir( $Dir ) )
      mkdir( $Dir, 0777, true );
    return $Dir;
  }

  protected $WorkingDir;

}

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

  public function getDataDir( $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->BaseDir . "/var/data", $createIfNotExists );
  }

  public function getTmpDir( $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->BaseDir . "/var/tmp", $createIfNotExists );
  }

  function createDirIfNotExists( $Dir )
  {
    $this->createIfNotExists( $Dir, true );
  }

  protected function createIfNotExists( $Dir, $createIfNotExists )
  {
    if ( $createIfNotExists && !is_dir( $Dir ) )
      mkdir( $Dir, 0777, true );
    return $Dir;
  }

  protected $BaseDir;

}

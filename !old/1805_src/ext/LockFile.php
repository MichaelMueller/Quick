<?php

namespace qck\ext;

/**
 * Description of BasicGit
 *
 * @author muellerm
 */
class LockFile implements interfaces\LockFile
{

  function __construct( $lockFile )
  {
    $this->lockFile = $lockFile;
  }

  function lock()
  {
    if ( $this->isLocked() )
      return;

    if ( $this->lockFile )
    {
      if ( !file_exists( $this->lockFile ) )
        touch( $this->lockFile );
      $this->handle = fopen( $this->lockFile, 'w' );
      $waitIfLocked = 1;
      $locked = flock( $this->handle, LOCK_EX, $waitIfLocked );
      if ( !$locked )
        throw new Exception( "cannot lock " . $this->lockFile );
    }
  }

  function unlock()
  {
    if ( $this->handle )
    {
      flock( $this->handle, LOCK_UN );
      fclose( $this->handle );
    }
    $this->handle = null;
  }

  public function __destruct()
  {
    $this->unlock();
  }

  public function isLocked()
  {
    return $this->handle != null;
  }

  protected $lockFile = null;
  protected $handle = null;

}

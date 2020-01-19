<?php

namespace qck\ext;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
class PersistentObject extends DataObject implements interfaces\PersistentObject
{

  function __construct( $DataDir, $WriteAccess = false )
  {
    $this->load( $DataDir, $WriteAccess );
  }

  function save()
  {
    fwrite( $this->Fp, serialize( $this->Data ) );
    fflush( $this->Fp );
  }

  protected function load( $DataDir, $WriteAccess )
  {
    $File = $DataDir . DIRECTORY_SEPARATOR . str_replace( "\\", "_", get_class( $this ) ) . ".dat";

    if ( !file_exists( $File ) )
      touch( $File );

    $Size = filesize( $File );
    $Mode = $WriteAccess ? "w+" : "r";
    $this->Fp = fopen( $File, $Mode );
    $waitIfLocked = 1;
    $locked = flock( $this->Fp, ($WriteAccess ? LOCK_EX : LOCK_SH ), $waitIfLocked );
    if ( $locked === FALSE )
      throw new Exception( "cannot lock " . $File );

    if ( $Size > 0 )
    {
      $this->Data = unserialize( fread( $this->Fp, $Size ) );
    }
    if ( !$WriteAccess )
      $this->unlock();
  }

  protected function unlock()
  {
    if ( $this->Fp )
    {
      flock( $this->Fp, LOCK_UN );
      fclose( $this->Fp );
    }
    $this->Fp = null;
  }

  public function __destruct()
  {
    $this->unlock();
  }

  private $Fp;

}

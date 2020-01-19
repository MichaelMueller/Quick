<?php

namespace qck\ext\abstracts;

/**
 * a DataObject 
 */
abstract class Log implements \qck\ext\interfaces\Log
{

  abstract function getLogFilePath();

  protected function setup()
  {
    if ( $this->logFile )
      return;
    $this->logFile = $this->getLogFilePath();
    if ( !file_exists( $this->logFile ) )
    {
      if ( !file_exists( dirname( $this->logFile ) ) )
        mkdir( dirname( $this->logFile ), 0777, true );
      touch( $this->logFile );
    }
    $this->pid = substr( sha1( rand() ), 0, 3 );
    if ( $this->installAsErrorLog )
      ini_set( "error_log", $this->logFile );
  }

  function setInstallAsErrorLog( $installAsErrorLog )
  {
    $this->installAsErrorLog = $installAsErrorLog;
  }

  public function msg( $msg )
  {
    $this->setup();
    $prefix = date( '[d-M-Y H:i:s' );
    $prefix .= " " . date_default_timezone_get();
    $prefix .= ", pid " . $this->pid . "]: ";

    file_put_contents( $this->logFile, $prefix . $msg . PHP_EOL, FILE_APPEND );
  }

  private $logFile;
  private $pid;
  private $installAsErrorLog = false;

}

<?php

namespace Qck\Ext;

/**
 * a DataObject 
 */
abstract class FileLog implements \Qck\Interfaces\Log
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

  private $logFile;
  private $pid;
  private $installAsErrorLog = false;

}

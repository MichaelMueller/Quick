<?php

namespace Qck\Ext;

/**
 * Implementation of ILog
 *
 * @author muellerm
 */
class TextFileLog extends FileLog
{

  function __construct( $LogFilePath, $installAsErrorLog = false )
  {
    $this->LogFilePath = $LogFilePath;
    $this->setInstallAsErrorLog( $installAsErrorLog );
  }

  function getLogFilePath()
  {
    return $this->LogFilePath;
  }

  public function msg( $msg )
  {
    $this->setup();
    $prefix = date( '[d-M-Y H:i:s' );
    $prefix .= " " . date_default_timezone_get();
    $prefix .= ", pid " . $this->pid . "]: ";

    file_put_contents( $this->logFile, $prefix . $msg . PHP_EOL, FILE_APPEND );
  }

  protected $LogFilePath;

}

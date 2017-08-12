<?php

namespace qck\ext;

/**
 * Implementation of ILog
 *
 * @author muellerm
 */
class DailyLog extends \qck\abstracts\Log
{

  function __construct( $logDir, $installAsErrorLog = false )
  {
    $this->LogFilePath = $logDir . "/" . date( 'Y-m-d' ) . ".txt";
    $this->setInstallAsErrorLog( $installAsErrorLog );
  }

  function getLogFilePath()
  {
    return $this->LogFilePath;
  }

  protected $LogFilePath;

}

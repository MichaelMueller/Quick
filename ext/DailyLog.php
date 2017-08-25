<?php

namespace qck\ext;

/**
 * Implementation of ILog
 *
 * @author muellerm
 */
class DailyLog extends \qck\abstracts\Log
{

  function __construct( $logDir, $installAsErrorLog = false, $dateFormat = 'Y-m' )
  {
    $this->LogFilePath = $logDir . "/" . date( $dateFormat ) . ".txt";
    $this->setInstallAsErrorLog( $installAsErrorLog );
  }

  function getLogFilePath()
  {
    return $this->LogFilePath;
  }

  protected $LogFilePath;

}

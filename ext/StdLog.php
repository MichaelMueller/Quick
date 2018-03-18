<?php

namespace qck\ext;

/**
 * Implementation of ILog
 *
 * @author muellerm
 */
class StdLog extends abstracts\Log
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

  protected $LogFilePath;

}

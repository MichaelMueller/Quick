<?php

namespace Qck\Ext;

/**
 * Implementation of ILog
 *
 * @author muellerm
 */
class DailyTextFileLog extends TextFileLog
{

  function __construct( $logDir, $installAsErrorLog = false, $dateFormat = 'Y-m' )
  {
    parent::__construct( $logDir . "/" . date( $dateFormat ) . ".txt", $installAsErrorLog );
  }

}

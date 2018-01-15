<?php

namespace qck\backup;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class BackupSet implements interfaces\BackupSet
{

  function __construct( \qck\core\interfaces\AdminMailer $AdminMailer, $HostInfo,
                        $LastSentCacheFile )
  {
    $this->AdminMailer = $AdminMailer;
    $this->HostInfo = $HostInfo;
    $this->LastSentCacheFile = $LastSentCacheFile;
  }

  function add( interfaces\BackupTask $BackupTask )
  {
    $this->Tasks[] = $BackupTask;
  }

  function setQuiet( $Quiet )
  {
    $this->Quiet = $Quiet;
  }

  protected function getDateTime()
  {

    return date( 'd-M-Y H:i:s' ) . " " . date_default_timezone_get();
  }

  public function run()
  {
    $quiet = $this->Quiet;

    /* @var $config \mbits\server\scripts\abstracts\AppConfig */
    // set the time and memory limit to 8 hours
    set_time_limit( $this->TimeLimit );
    ini_set( 'memory_limit', $this->MemoryLimit );

    if ( !$quiet )
    {
      print PHP_EOL . "STARTING BACKUP " . $this->getDateTime() . PHP_EOL;
      print "*********************************************" . PHP_EOL;
    }

    // run the jobs and collect possible errors
    $ErrorLog = null;
    $CommandLog = null;

    for ( $i = 0; $i < count( $this->Tasks ); $i++ )
    {
      $Output = $quiet ? "" : false;
      $RetValue = 0;
      $Commands = [];
      if ( !$this->Tasks[ $i ]->exec( $Commands, $RetValue, $Output ) )
      {
        $Command = implode( PHP_EOL, $Commands );
        $ErrorLog .= sprintf( "The last command of the command list %s failed with code %s. " . PHP_EOL, $Command, $RetValue );
        if ( $quiet )
          $ErrorLog .= sprintf( "Output so far was:" . PHP_EOL . " %s" . PHP_EOL, strval( $Output ) );
      }
      else
      {
        $CommandLog .= implode( PHP_EOL, $Commands ) . PHP_EOL;
      }
    }

    if ( !$quiet )
    {
      flush();
      print PHP_EOL . "ENDING BACKUP " . $this->getDateTime() . PHP_EOL;
      print "*********************************************" . PHP_EOL;
    }
    $ErrorLog = str_replace( PHP_EOL, "\n", $ErrorLog );
    $CommandLog = str_replace( PHP_EOL, "\n", $CommandLog );
    // send error log if necessary
    if ( $ErrorLog )
    {
      if ( $quiet )
        $this->AdminMailer->sendToAdmin( "Backup Errors on " . $this->HostInfo . " (" . $this->getDateTime() . ")", $ErrorLog );
    }
    else
    {
      // check when we have sent it the last time
      $LastSentFile = $this->LastSentCacheFile;
      $Now = time();
      $LastSent = file_exists( $LastSentFile ) ? intval( file_get_contents( $LastSentFile ) ) : $Now - $this->StatusTimeout;
      if ( $Now - $LastSent >= $this->StatusTimeout )
      {
        $this->AdminMailer->sendToAdmin( "Backup Log From " . $config->getHostInfo() . " (" . $this->getDateTime() . ")", "All commands successfully run: " . PHP_EOL . $CommandLog );
        file_put_contents( $LastSentFile, $Now );
      }
    }

    return null;
  }

  protected $Tasks = [];
  protected $Quiet = false;

  /**
   *
   * @var \qck\core\interfaces\AdminMailer
   */
  protected $AdminMailer;
  protected $HostInfo;
  protected $LastSentCacheFile;

}

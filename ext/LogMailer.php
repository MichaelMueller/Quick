<?php

namespace qck\ext;

/**

 *
 * @author micha
 */
class Backup implements \qck\core\interfaces\Functor
{

  function __construct( \qck\core\interfaces\AdminMailer $AdminMailer, $LogFile,
                        $ClearLogFile = true, $HostInfo = null )
  {
    $this->AdminMailer = $AdminMailer;
    $this->LogFile = $LogFile;
    $this->ClearLogFile = $ClearLogFile;
    $this->HostInfo = $HostInfo ? $HostInfo : gethostname();
  }

  function run()
  {
    if ( !file_exists( $this->LogFile ) )
      return;
    $contents = file_get_contents( $this->LogFile );
    $this->AdminMailer->sendToAdmin( "Log file from " . $this->HostInfo, $this->LogFile . ":\n\n" . $contents );
    if ( $this->ClearLogFile )
      file_put_contents( $this->LogFile, "", LOCK_EX );
  }

  /**
   *
   * @var \qck\core\interfaces\AdminMailer
   */
  protected $AdminMailer;

  /**
   *
   * @var string
   */
  protected $LogFile;

  /**
   *
   * @var bool 
   */
  protected $ClearLogFile;

  /**
   *
   * @var string 
   */
  protected $HostInfo;

}

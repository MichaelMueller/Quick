<?php

namespace qck\ext;

/**
 * The Backup Controller runs a set of backup system commands and writes output to a 
 * log file.
 * If one of the commands does not end with an expected return value, the script will send an alert mail to an administrator.
 * Furthermore the controller will send regular backup logs after a certain amount of time 
 *
 * @author micha
 */
class Backup implements \qck\core\interfaces\Functor
{

  function __construct( \qck\core\interfaces\AdminMailer $AdminMailer, $LogFile,
                        array $Cmds = array (),
                        array $AllowedReturnValuesPerCmd = array () )
  {
    $this->AdminMailer = $AdminMailer;
    $this->LogFile = $LogFile;
    $this->Cmds = $Cmds;
    $this->AllowedReturnValuesPerCmd = $AllowedReturnValuesPerCmd;
  }

  function addCmd( \qck\ext\interfaces\Cmd $Cmd, $AllowedReturnValues = [ 0 ] )
  {
    $this->Cmds[] = $Cmd;
    $this->AllowedReturnValuesPerCmd[] = $AllowedReturnValues;
  }

  function run()
  {
    $FailedMsg = null;
    for ( $i = 0; $i < count( $this->Cmds ); $i++ )
    {
      /* @var $Cmd interfaces\Cmd */
      $Cmd = $this->Cmds[ $i ];
      $AllowedReturnValues = $this->AllowedReturnValuesPerCmd[ $i ];
      $Output = 0;
      $Retval = $Cmd->run( $Output );
      file_put_contents( $this->LogFile, $Output, LOCK_EX | FILE_APPEND );
      if ( !in_array( $Retval, $AllowedReturnValues ) )
        $FailedMsg .= "ERROR: Command " . $Cmd->getCmdString() . " failed with return code (" . $Retval . ")\nOutput was: " . $Output . "\n";
    }
    if ( $FailedMsg )
    {
      $this->AdminMailer->sendToAdmin( "Backup errors on host " . gethostname(), $FailedMsg );
    }
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
   * @var string
   */
  protected $Cmds = [];

  /**
   *
   * @var array
   */
  protected $AllowedReturnValuesPerCmd = [];

}

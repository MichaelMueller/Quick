<?php

namespace qck\backup\abstracts;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
abstract class BackupTask implements \qck\backup\interfaces\BackupTask
{

  /**
   * @return array an array of string commands
   */
  abstract protected function getCommands();

  function addPreCommand( $cmd )
  {
    $this->PreCommands[] = $cmd;
  }

  function addPostCommand( $cmd )
  {
    $this->PostCommands[] = $cmd;
  }
  function addTidyUpCommand( $cmd )
  {
    $this->TidyUpCommand[] = $cmd;
  }


  public function exec( &$commands, &$lastReturnCode, &$output= false )
  {
    $cmds = $this->getCommands();

    $cmds = array_merge( $this->PreCommands, $cmds );
    $cmds = array_merge( $cmds, $this->PostCommands );

    $oldCwd = getcwd();
    if ( $this->WorkingDir )
      chdir( $this->WorkingDir );

    if ( $output !== false )
      ob_start();


    foreach ( $cmds as $cmd )
    {
      $commands[] = $cmd;
      print $cmd . PHP_EOL;

      $lastReturnCode = 0;
      system( $cmd, $lastReturnCode );

      if ( $lastReturnCode != 0 )
        break;
    }

    foreach ( $this->TidyUpCommand as $cmd )
    {
      print $cmd . PHP_EOL;
      system( $cmd );            
    }
    
    if ( $output !== false )
      $output = ob_get_clean();

    if ( $this->WorkingDir )
      chdir( $oldCwd );

    return $lastReturnCode == 0 ? true : false;
  }

  function setWorkingDir( $WorkingDir )
  {
    $this->WorkingDir = $WorkingDir;
  }

  protected $WorkingDir;
  protected $PreCommands = [];
  protected $PostCommands = [];
  protected $TidyUpCommand = [];

}

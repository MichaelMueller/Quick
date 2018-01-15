<?php

namespace qck\backup\interfaces;

/**
 *
 * @author muellerm
 */
interface BackupTask
{

  /**
   * 
   * @param array $commands [out] an array of system commands issued until an error occured
   * @param int $returnCode [out] the return value of the last command
   * @param type $output [out] the output so far. if $output is false, the output of the command is immediately printed, otherwise it will be saved to this var
   * @return bool true if all command return codes are 0, false otherwise
   */
  public function exec( &$commands, &$lastReturnCode, &$output = false );

  /**
   * add another command to execute before the main command(s)
   * @param string $cmd
   */
  function addPreCommand( $cmd );

  /**
   * add another command to execute after the main command(s)
   * @param string $cmd
   */
  function addPostCommand( $cmd );

  /**
   * add another command to execute after the main command(s) which will be executed in any case
   * @param string $cmd
   */
  function addTidyUpCommand( $cmd );
}

<?php

namespace qck\ext;

/**
 * Command
 *
 * This class represents a shell command.
 */
class CmdLineRunner implements interfaces\CmdLineRunner
{

  function __construct( $cmd = null )
  {
    $this->cmd = $cmd;
  }

  function runCmd( &$output )
  {
    return $this->run( $this->cmd, $output );
  }

  function run( $cmd, &$output )
  {
    $returnVal = 1;
    $theCmd = "$cmd 2>&1";
    exec( $theCmd, $output, $returnVal );
    $output = implode( "\n", $output );
    //error_log( "command " . $theCmd . " was run with return value " . $returnVal . " and output " . $output, E_USER_NOTICE );
    
    return $returnVal;
  }

  protected $cmd;

}

<?php

namespace Qck\Ext;

/**
 * Command
 *
 * This class represents a shell command.
 */
class CmdLineRunner implements \Qck\Interfaces\CmdLineRunner
{

  function run( $Cmd, &$Output = false, $StartDir = null )
  {
    // change WorkingDir if needed ( remember old working dir )
    $cwd = null;
    if ( $StartDir )
    {
      $cwd = getcwd();
      chdir( $StartDir );
    }
    // if $Output is not null
    if ( $Output !== false )
      ob_start();

    // run command
    $retVal = -1;
    if ( !system( $Cmd, $retVal ) )
      $retVal = -1;

    // get $Output if neessary
    if ( $Output !== false )
      $Output = ob_get_clean();

    // change back cwd if necessary
    if ( $cwd )
      chdir( $cwd );
    return $retVal;
  }

  protected $Cmd;

}

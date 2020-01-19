<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface ScriptRunner
{

  /**
   * will create a temp script file, run it via exec or similar, return the return value of the commandline
   * execution and delete the temp script file
   * 
   * @param string $scriptContents
   * @param string $command [optional] the command to be prefixed in front of the script (e.g. C:\Python27\python.exe)
   * @param string $output [optional] the output returned from the command line call
   * @param string $scriptExt [optional] a file extension
   * 
   * @return int return the return value of the commandline execution
   */
  public function run( $scriptContents, &$output = null, $command = null,
                       $scriptExt = null );
}

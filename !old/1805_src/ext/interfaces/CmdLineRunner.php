<?php

namespace qck\ext\interfaces;

/**
 *
 * @author muellerm
 */
interface CmdLineRunner
{

  /**
   * runs a command on the system
   * @param type $cmd
   * @param type $output ref to the output. STDERR should always be redirected to STDOUT
   * @return int the return value of the command
   */
  function run( $cmd, &$output );
}

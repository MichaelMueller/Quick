<?php

namespace qck\ext\interfaces;

/**
 * Basic interface for a system command
 *
 * @author muellerm
 */
interface Cmd
{

  /**
   * runs a command
   * @param string $output if set to false output will be printed
   * @return int the return value of the command or -1 if it failed for any reason
   */
  function run( &$output = false );

  /**
   * @return string 
   */
  function __toString();

  /**
   * @return string 
   */
  function getStartDirectory();
}

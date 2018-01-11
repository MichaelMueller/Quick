<?php
namespace qck\backup\interfaces;

/**
 *
 * @author muellerm
 */
interface BackupTask
{
  /** 
   * @param string $output
   * @return bool
   */  
  public function exec(&$output, &$returnCode, &$command);
  
}

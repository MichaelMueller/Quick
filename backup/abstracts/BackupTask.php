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

  public function exec(&$commands, &$lastReturnCode, &$output = false)
  {
    $cmds = $this->getCommands();

    $oldCwd = getcwd();
    if($this->WorkingDir)
      chdir($this->WorkingDir);
    
    if ($output !== false)
      ob_start();

      
    foreach ($cmds as $cmd)
    {
      $commands[] = $cmd;
      print $cmd . PHP_EOL;
      flush();

      $lastReturnCode = 0;
      passthru($cmd, $lastReturnCode);
      flush();
      
      if ($lastReturnCode != 0)
        break;
    }
    
    if ($output !== false)
      $output = ob_end_clean();

    if($this->WorkingDir)
      chdir($oldCwd);

    return $lastReturnCode == 0 ? true : false;
  }

  function setWorkingDir($WorkingDir)
  {
    $this->WorkingDir = $WorkingDir;
  }

  protected $WorkingDir;

}

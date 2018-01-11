<?php

namespace qck\backup\abstracts;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
abstract class BackupTask implements \qck\backup\interfaces\BackupTask
{

  function __construct($Quiet = false, $DryRun = false)
  {
    $this->Quiet = $Quiet;
    $this->DryRun = $DryRun;
  }

  abstract public function getCommand();

  public function exec(&$output, &$returnCode, &$command)
  {
    return $this->runCmd($output, $returnCode, $command);
  }

  protected function commandCanSimulateDryRun()
  {
    return false;
  }

  protected function runCmd(&$output, &$returnCode, &$command)
  {
    $command = $this->getCommand(); // . " 2>&1";
    if (!$this->Quiet)
      print "Command is " . $command . PHP_EOL;
    if ($this->DryRun && $this->commandCanSimulateDryRun() == false)
      return true;

    $descriptorspec = array(
        0 => array("pipe", "r"), // stdin is a pipe that the child will read from
        1 => array("pipe", "w"), // stdout is a pipe that the child will write to      
        2 => array("pipe", "w")   // stderr is a pipe that the child will write to
    );
    $pipes = array();
    $process = proc_open($command, $descriptorspec, $pipes);

    while (($s = fgets($pipes[1])))
    {
      $strOut = $s;
      if (!$this->Quiet)
      {
        print $strOut;
        flush();
      }
      $output .= $strOut;
    }
    // stderr
    $strErrOut = is_resource($pipes[2]) && !feof($pipes[2]) ? fgets($pipes[2]) : "";
    if (!$this->Quiet)
    {
      print $strErrOut;
      flush();
    }
    $output .= $strErrOut;

    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $returnCode = proc_close($process);
    return $returnCode == 0;
  }

  /**
   *
   * @var bool
   */
  protected $DryRun;

  /**
   *
   * @var bool
   */
  protected $Quiet;

}

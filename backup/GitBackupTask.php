<?php

namespace qck\backup;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class GitBackupTask extends abstracts\BackupTask
{

  function __construct($GitDir)
  {
    $this->setWorkingDir($GitDir);
  }

  function setGitCommand($GitCommand)
  {
    $this->GitCommand = $GitCommand;
  }

  function setTarget($Target)
  {
    $this->Target = $Target;
  }

  function setBranch($Branch)
  {
    $this->Branch = $Branch;
  }

  public function getCommands()
  {
    $cmds = [];

    $git = $this->GitCommand;
    $cmds[] = $git." add -A";
    $DateTime = date('d-M-Y H:i:s') . " " . date_default_timezone_get();
    $commitMsg = "Commit of " . $DateTime;
    $cmds[] = $git.' commit -am"'.$commitMsg.'"';
    $cmds[] = $git." push ".$this->Target." ".$this->Branch;
    
    return $cmds;
  }

  /**
   *
   * @var bool
   */
  protected $GitDir;

  /**
   *
   * @var string
   */
  protected $GitCommand = "git";

  /**
   *
   * @var string
   */
  protected $Target = "origin";

  /**
   *
   * @var string
   */
  protected $Branch = "Master";

}

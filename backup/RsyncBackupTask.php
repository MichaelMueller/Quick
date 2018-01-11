<?php

namespace qck\backup;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class RsyncBackupTask extends abstracts\BackupTask
{

  function __construct($Origin, $Target)
  {
    $this->Origin = $Origin;
    $this->Target = $Target;
  }

  function setCygwinDir($CygwinDir)
  {
    $this->setWorkingDir($CygwinDir);
    $this->RsyncCommand = ".\\rsync.exe";
    $this->SshCommand = ".\\ssh.exe";
  }

  function setSsh($Ssh)
  {
    $this->Ssh = $Ssh;
  }

  function setSshCommand($SshCommand)
  {
    $this->SshCommand = $SshCommand;
  }

  function setShowProgress($ShowProgress)
  {
    $this->ShowProgress = $ShowProgress;
  }

  function setSshPort($SshPort)
  {
    $this->SshPort = $SshPort;
  }

  function setDelete($Delete)
  {
    $this->Delete = $Delete;
  }

  function setBackupDir($BackupDir)
  {
    $this->BackupDir = $BackupDir;
  }

  function setShowStats($ShowStats)
  {
    $this->ShowStats = $ShowStats;
  }

  function setRsyncCommand($RsyncCommand)
  {
    $this->RsyncCommand = $RsyncCommand;
  }

  function setQuiet($Quiet)
  {
    $this->Quiet = $Quiet;
  }

  function setDryRun($DryRun)
  {
    $this->DryRun = $DryRun;
  }

  function getCommands()
  {
    $cmds = [];

    $rsync = $this->RsyncCommand;
    $cmd = $rsync . " -a -h";
    $cmd .= $this->Ssh ? ' -z -e "' . $this->SshCommand . ' -p ' . $this->SshPort . '"' : "";
    $cmd .= $this->Delete ? ' --delete' : "";
    $cmd .= $this->BackupDir ? ' -b --backup-dir="' . $this->BackupDir . '"' : "";
    $cmd .= $this->Quiet ? ' --quiet' : " -v";
    $cmd .= $this->ShowStats ? ' --stats' : "";
    $cmd .= $this->DryRun ? ' -n' : "";
    $cmd .= $this->ShowProgress ? ' --progress' : "";
    $cmd .= ' ' . $this->Origin . ' ' . $this->Target;
    $cmds[] = $cmd;
    return $cmds;
  }

  /**
   *
   * @var bool
   */
  protected $Origin;

  /**
   *
   * @var bool
   */
  protected $Quiet = false;

  /**
   *
   * @var bool
   */
  protected $DryRun = false;

  /**
   *
   * @var string
   */
  protected $Target;

  /**
   *
   * @var bool
   */
  protected $Ssh = false;

  /**
   *
   * @var bool 
   */
  protected $ShowProgress = false;

  /**
   *
   * @var string
   */
  protected $SshCommand = "ssh";

  /**
   *
   * @var int
   */
  protected $SshPort = 22;

  /**
   *
   * @var bool
   */
  protected $Delete = true;

  /**
   *
   * @var string 
   */
  protected $BackupDir = null;

  /**
   *
   * @var bool 
   */
  protected $ShowStats = false;

  /**
   *
   * @var string
   */
  protected $RsyncCommand = "rsync";

}

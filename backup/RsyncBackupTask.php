<?php

namespace qck\backup;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class RsyncBackupTask extends abstracts\BackupTask
{

  function __construct( $Origin, $Target, $Quiet = false, $DryRun = false )
  {
    $this->Origin = $Origin;
    $this->Target = $Target;
    parent::__construct( $Quiet, $DryRun );
  }

  function setSsh( $Ssh )
  {
    $this->Ssh = $Ssh;
  }

  function setSshPort( $SshPort )
  {
    $this->SshPort = $SshPort;
  }

  function setDelete( $Delete )
  {
    $this->Delete = $Delete;
  }

  function setBackupDir( $BackupDir )
  {
    $this->BackupDir = $BackupDir;
  }

  function setShowStats( $ShowStats )
  {
    $this->ShowStats = $ShowStats;
  }

  function setRsyncCommand( $RsyncCommand )
  {
    $this->RsyncCommand = $RsyncCommand;
  }

  protected function commandCanSimulateDryRun()
  {
    return true;
  }

  function getCommand()
  {
    $rsync = $this->RsyncCommand;

    $cmd = $rsync . " -a -h";
    $cmd .= $this->Ssh ? ' -z -e "ssh -p ' . $this->SshPort . '"' : "";
    $cmd .= $this->Delete ? ' --delete' : "";
    $cmd .= $this->BackupDir ? ' -b --backup-dir="' . $this->BackupDir . '"' : "";
    $cmd .= $this->Quiet ? ' --quiet' : " -v";
    $cmd .= $this->ShowStats ? ' --stats' : "";
    $cmd .= $this->DryRun ? ' -n' : "";
    $cmd .= ' ' . $this->Origin . ' ' . $this->Target;
    return $cmd;
  }

  /**
   *
   * @var bool
   */
  protected $Origin;

  /**
   *
   * @var string
   */
  protected $Target;

  /**
   *
   * @var bool
   */
  protected $Ssh;

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

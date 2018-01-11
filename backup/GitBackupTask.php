<?php

namespace qck\backup;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class GitBackupTask extends abstracts\BackupTask
{

  function __construct( $GitDir, $Quiet = false, $DryRun = false )
  {
    $this->GitDir = $GitDir;
    parent::__construct( $Quiet, $DryRun );
  }

  function setGitCommand( $GitCommand )
  {
    $this->GitCommand = $GitCommand;
  }

  function setTarget( $Target )
  {
    $this->Target = $Target;
  }

  function setBranch( $Branch )
  {
    $this->Branch = $Branch;
  }

  public function getCommand()
  {
    $DateTime = date( 'd-M-Y H:i:s' ) . " " . date_default_timezone_get();
    $commitMsg = "Commit of " . $DateTime;
    $git = $this->GitCommand;
    return sprintf( '%s add -A && %s commit -am"%s" && %s push %s %s', $git, $git, $commitMsg, $git, $this->Target, $this->Branch );
  }

  public function exec( &$output, &$returnCode, &$command )
  {
    $cwd = getcwd();
    chdir( $this->GitDir );
    $retValue = parent::exec( $output, $returnCode, $command );
    chdir( $cwd );
    return $retValue;
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

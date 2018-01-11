<?php

namespace qck\backup;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class MysqlBackupTask extends abstracts\BackupTask
{

  function __construct( $User, $Pass, $DumpFile )
  {
    $this->User = $User;
    $this->Pass = $Pass;
    $this->DumpFile = $DumpFile;
  }

  function setDatabases( array $Databases )
  {
    $this->Databases = $Databases;
  }

  function setMysqlCommand( $MysqlCommand )
  {
    $this->MysqlCommand = $MysqlCommand;
  }

  function setHost( $Host )
  {
    $this->Host = $Host;
  }

  function getCommands()
  {
    $cmds = [];

    $cmd = $this->MysqlCommand . " -u" . $this->User . " -p" . $this->Pass . " -h" . $this->Host;
    $cmd .= " --events --ignore-table=mysql.event --skip-lock-tables --flush-privileges";
    $cmd .= $this->Databases ? " --databases " . implode( " ", $this->Databases ) : " --all-databases";
    $cmd .= " > " . $this->DumpFile;
    $cmds[] = $cmd;
    return $cmds;
  }

  protected $User;
  protected $Pass;
  protected $Host = "localhost";
  protected $DumpFile;
  protected $Databases;
  protected $MysqlCommand = "mysqldump";

}

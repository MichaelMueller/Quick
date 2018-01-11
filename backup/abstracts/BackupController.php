<?php

namespace qck\backup\abstracts;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
abstract class BackupController implements \qck\core\interfaces\Controller
{

  /**
   * @return \qck\backup\interfaces\BackupSet A BackupSet
   */
  abstract protected function getBackupSet(\qck\core\interfaces\AppConfig $config, $quiet, $dryRun);

  protected function getDateTime()
  {

    return date('d-M-Y H:i:s') . " " . date_default_timezone_get();
  }

  public function run(\qck\core\interfaces\AppConfig $config)
  {
    $quiet = false;
    $dryRun = false;

    /* @var $config \muellerm\server\scripts\abstracts\AppConfig */
    // set the time and memory limit to 8 hours
    set_time_limit($this->TimeLimit);
    ini_set('memory_limit', $this->MemoryLimit);
    $quiet = array_search("--quiet", $config->getControllerFactory()->getArgv()) !== false;
    $dryRun = array_search("--dryRun", $config->getControllerFactory()->getArgv()) !== false;

    if (!$quiet)
    {
      print PHP_EOL . "STARTING BACKUP " . $this->getDateTime() . PHP_EOL;
      print "*********************************************" . PHP_EOL;
    }

    // run the jobs and collect possible errors
    $ErrorLog = null;
    $CommandLog = null;
    $BackupSet = $this->getBackupSet($config, $quiet, $dryRun);
    for ($i = 0; $i < $BackupSet->size(); $i++)
    {
      $Output = "";
      $RetValue = 0;
      $Command = 0;
      if (!$BackupSet->at($i)->exec($Output, $RetValue, $Command))
        $ErrorLog .= sprintf("The command %s failed with code %d. Output was:" . PHP_EOL . " %s" . PHP_EOL, $Command, $RetValue, $Output);
      else
        $CommandLog .= $Command . PHP_EOL;
    }

    if (!$quiet)
    {
      print PHP_EOL . "ENDING BACKUP " . $this->getDateTime() . PHP_EOL;
      print "*********************************************" . PHP_EOL;
    }
    $ErrorLog = str_replace(PHP_EOL, "\n", $ErrorLog);
    $CommandLog = str_replace(PHP_EOL, "\n", $CommandLog);
    // send error log if necessary
    if ($ErrorLog)
      if ($quiet)
        $config->getAdminMailer()->sendToAdmin("Backup Errors on " . $config->getHostInfo() . " (" . $this->getDateTime() . ")", $ErrorLog);
      else
      {
        // check when we have sent it the last time
        $LastSentFile = $config->getCacheDir() . DIRECTORY_SEPARATOR . "lastsent.date";
        $Now = time();
        $LastSent = file_exists($LastSentFile) ? intval(file_get_contents($LastSentFile)) : $Now - $this->StatusTimeout;
        if ($Now - $LastSent >= $this->StatusTimeout)
        {
          $config->getAdminMailer()->sendToAdmin("Backup Log From " . $config->getHostInfo() . " (" . $this->getDateTime() . ")", "All commands successfully run: " . PHP_EOL . $CommandLog);
          file_put_contents($LastSentFile, $Now);
        }
      }

    return null;
  }

  /**
   *
   * @var interfaces\BackupSet 
   */
  protected $BackupSet;
  protected $TimeLimit = 28800;
  protected $MemoryLimit = "128M";
  // one week
  protected $StatusTimeout = 604800;

}

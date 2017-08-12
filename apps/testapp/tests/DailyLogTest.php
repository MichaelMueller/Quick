<?php

namespace qck\apps\testapp\tests;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class DailyLogTest extends \qck\abstracts\Test
{
  public function run( \qck\interfaces\AppConfig $config )
  {
    $Dir = $this->getTempDir("Log", true);
    $DailyLog = new \qck\ext\DailyLog($Dir);
    $DailyLog->msg("Test");
    $LogFile = $DailyLog->getLogFilePath();
    $this->assert(filesize($LogFile)>0, "No Message logged");
  }

  public function getRequiredTests()
  {
    return array();
  }
}

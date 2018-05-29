<?php

namespace qck\ext\tests;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class DailyLogTest extends \qck\core\abstracts\Test
{
  public function run( \qck\core\interfaces\AppConfig $config, array &$FilesToDelete = []  )
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

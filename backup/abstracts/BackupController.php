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
  abstract protected function fillBackupSet( \qck\core\interfaces\AppConfig $config );

  public function run( \qck\core\interfaces\AppConfig $config )
  {
    $quiet = array_search( "--quiet", $config->getControllerFactory()->getArgv() ) !== false;
    $LastSentFile = $config->getCacheDir() . DIRECTORY_SEPARATOR . "lastsent.date";
    $BackupSet = new \qck\backup\BackupSet( $config->getAdminMailer(), $config->getHostInfo(), $LastSentFile );
    $BackupSet->setQuiet($quiet);
    
    $this->fillBackupSet( $BackupSet );
    $BackupSet->run();
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

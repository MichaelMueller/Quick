<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class AppStarter implements Interfaces\Functor
{

  function __construct(Interfaces\DirectoryConfig $DirectoryConfig)
  {
    $this->DirectoryConfig = $DirectoryConfig;
  }

  function run()
  {

// 1. detect host specific App Creator File
    $Dir           = $this->DirectoryConfig->getDataDir() . "/" . gethostname();
    $CreateAppFile = $Dir . "/" . $this->CreateAppFileName;

    if (!file_exists($CreateAppFile))
    {
      $this->DirectoryConfig->createDirIfNotExists($Dir);
      touch($CreateAppFile);
      $Error = sprintf("Cannot find host specific App Creator file. " .
              "Please create %s and return a valid \\Qck\\Interfaces\\App instance", $CreateAppFile);
      throw new \Exception($Error, Interfaces\Response::EXIT_CODE_INTERNAL_ERROR);
    }

// 2. create & start app
    /* @var $App Qck\Interfaces\App */
    $App = require_once( $CreateAppFile );
    $App->setDirectoryConfig($this->DirectoryConfig);
    $App->run();
  }

  protected $DirectoryConfig;
  protected $CreateAppFileName = "createApp.php";

}

<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class AppStarter implements Interfaces\Functor, Interfaces\DirectoryConfig
{

  function __construct( $BaseDir )
  {
    $this->BaseDir = $BaseDir;
  }

  function run()
  {

// 3. detect host specific App Creator File
    $Dir           = $this->BaseDir . "/" . $this->WorkingSubDir . "/" . $this->DataSubDir . "/" . gethostname();
    $CreateAppFile = $Dir . "/" . $this->CreateAppFileName;

    if ( !file_exists( $CreateAppFile ) )
    {
      $Error = sprintf( "Cannot find host specific App Creator file. " .
          "Please create %s and return a valid \\Qck\\Interfaces\\App instance", $CreateAppFile );
      throw new \Exception( $Error, Interfaces\Response::EXIT_CODE_INTERNAL_ERROR );
    }

// 4. create & start app
    /* @var $App Qck\Interfaces\App */
    $App = require_once( $CreateAppFile );
    $App->setDirectoryConfig( $this );
    $App->run();
  }

  function getBaseDir()
  {
    return $this->BaseDir;
  }

  function setCreateAppFileName( $CreateAppFileName )
  {
    $this->CreateAppFileName = $CreateAppFileName;
  }

  public function getDataDir( $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->WorkingDir . "/data", $createIfNotExists );
  }

  public function getTmpDir( $createIfNotExists = true )
  {
    return $this->createIfNotExists( $this->WorkingDir . "/tmp", $createIfNotExists );
  }

  protected function createIfNotExists( $Dir, $createIfNotExists )
  {
    if ( $createIfNotExists && !is_dir( $Dir ) )
      mkdir( $Dir, 0777, true );
    return $Dir;
  }

  protected $BaseDir;
  protected $WorkingSubDir     = "var";
  protected $DataSubDir        = "data";
  protected $CreateAppFileName = "createApp.php";

}

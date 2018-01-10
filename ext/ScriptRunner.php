<?php

namespace qck\ext;

/**
 *
 * @author muellerm
 */
class ScriptRunner implements \qck\interfaces\ScriptRunner
{

  function __construct( interfaces\CmdLineRunner $Runner, $tempDir = null )
  {
    $this->Runner = $Runner;
    $this->TempDir = $tempDir ? realpath( $tempDir ) : sys_get_temp_dir();
  }

  public function run( $scriptContents, &$output = null, $command = null, $scriptExt = null )
  {
    $scriptFile = null;
    do
    {
      $scriptFile = $this->TempDir . DIRECTORY_SEPARATOR . uniqid() . $scriptExt;
    }
    while ( file_exists( $scriptFile ) );
    touch($scriptFile);

    try
    {
      file_put_contents( $scriptFile, $scriptContents );
      $output = "";
      $retValue = $this->Runner->run( $command . " " . $scriptFile, $output );
      unlink( $scriptFile );
      return $retValue;
    }
    catch ( Exception $e )
    {
      unlink( $scriptFile );
      throw $e;
    }
  }

  /**
   *
   * @var interfaces\CmdLineRunner 
   */
  protected $Runner;
  protected $TempDir;

}

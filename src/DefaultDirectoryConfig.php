<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class DefaultDirectoryConfig implements Interfaces\DirectoryConfig, Interfaces\HostNameProvider
{

  const WORKING_DIR                = "var";
  const DATA_SUBDIR                = "data";
  const TMP_SUBDIR                 = "tmp";
  const LOCAL_SUBDIR               = "local";
  const APP_CREATION_FILE_BASENAME = "createApp.php";

  /**
   * @return Interfaces\Mail\AdminMailer
   */
  static function getAppCreationFilePath( $ProjectDir )
  {
    if ( ! self::$HostName )
      self::$HostName = gethostname();

    $CreateAppFileParentDirPath = $ProjectDir . "/" . self::WORKING_DIR . "/" . self::LOCAL_SUBDIR . "/" . self::$HostName;
    return self::join( $CreateAppFileParentDirPath, self::APP_CREATION_FILE_BASENAME );
  }

  function __construct( $ProjectDir )
  {
    $this->ProjectDir = $ProjectDir;
  }

  function getProjectDir( $SubFilePath = null )
  {
    return $this->join( $this->ProjectDir, $SubFilePath );
  }

  public function getDataDir( $SubFilePath = null, $createIfNotExists = true, $IsDir = true )
  {
    $Dir = $this->ProjectDir . "/" . self::WORKING_DIR . "/" . self::DATA_SUBDIR;
    return $this->createIfNotExists( $this->join( $Dir, $SubFilePath ), $createIfNotExists, $IsDir );
  }

  public function getLocalDataDir( $SubFilePath = null, $createIfNotExists = true, $IsDir = true )
  {
    $Dir = $this->ProjectDir . "/" . self::WORKING_DIR . "/" . self::LOCAL_SUBDIR . "/" . $this->getHostName();
    return $this->createIfNotExists( $this->join( $Dir, $SubFilePath ), $createIfNotExists, $IsDir );
  }

  public function getTmpDir( $SubFilePath = null, $createIfNotExists = true, $IsDir = true )
  {
    $Dir = $this->ProjectDir . "/" . self::WORKING_DIR . "/" . self::TMP_SUBDIR;
    return $this->createIfNotExists( $this->join( $Dir, $SubFilePath ), $createIfNotExists, $IsDir );
  }

  static function join( $ProjectPath, $FileName )
  {
    $Path = $ProjectPath . ($FileName !== null ? DIRECTORY_SEPARATOR . $FileName : "");
    return strpos( $Path, "\\" ) !== false ? str_replace( "/", "\\", $Path ) : str_replace( "\\", "/", $Path );
  }

  protected function createIfNotExists( $FilePath, $createIfNotExists, $IsDir )
  {
    if ( $createIfNotExists && ! file_exists( $FilePath ) )
    {
      if ( $IsDir )
        mkdir( $FilePath, 0777, true );
      else
      {
        $ParentDir = dirname( $FilePath );
        if ( ! is_dir( $ParentDir ) )
          mkdir( $ParentDir, 0777, true );
        touch( $FilePath );
      }
    }
    return $FilePath;
  }

  public function getHostName()
  {
    if ( ! self::$HostName )
      self::$HostName = gethostname();
    return self::$HostName;
  }

  protected $ProjectDir;
  protected static $HostName;

}

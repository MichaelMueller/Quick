<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class File implements \Qck\Interfaces\File
{

  static function createFromDirAndBasename( $ParentDir, $FileBasename )
  {
    $File = new File;
    $File->setFilePath( self::join( $ParentDir, $FileBasename ) );
    return $File;
  }

  static function createFromPath( $FilePath )
  {
    $File = new File;
    $File->setFilePath( $FilePath );
    return $File;
  }

  function setFilePath( $FilePath )
  {
    $this->FilePath = $FilePath;
    if ( $this->ObjectStorage )
      $this->ObjectStorage->setScalar( "FilePath", $FilePath );
  }

  public function getBasename()
  {
    return pathinfo( $this->getPath(), PATHINFO_BASENAME );
  }

  public function getExtension()
  {
    return pathinfo( $this->getPath(), PATHINFO_EXTENSION );
  }

  public function getFileName()
  {
    return pathinfo( $this->getPath(), PATHINFO_FILENAME );
  }

  public function getPath()
  {
    return $this->ObjectStorage ? $this->ObjectStorage->get( "FilePath" ) : $this->FilePath;
  }

  public function isDir()
  {
    return is_dir( $this->getPath() );
  }

  public function readContents()
  {
    $FilePath = $this->getPath();
    if ( !file_exists( $FilePath ) || filesize( $FilePath ) == 0 )
      return null;

    $f       = fopen( $FilePath, "r" );
    flock( $f, LOCK_SH );
    $content = fread( $f, filesize( $FilePath ) );
    flock( $f, LOCK_UN );
    fclose( $f );
    return $content;
  }

  public function writeContents( $Data )
  {
    file_put_contents( $this->getPath(), $Data, LOCK_EX );
  }

  public function getParentDir()
  {
    return dirname( $this->getPath() );
  }

  static function join( $BasePath, $FileName )
  {
    $Path = $BasePath . "/" . $FileName;

    return strpos( $Path, "\\" ) !== false ? str_replace( "/", "\\", $Path ) : $Path;
  }

  public function getSize()
  {
    return filesize( $this->getPath() );
  }

  public function getObjectStorage()
  {
    return $this->ObjectStorage;
  }

  public function setObjectStorage( Interfaces\ObjectStorage $ObjectStorage )
  {
    $this->ObjectStorage = $ObjectStorage;
  }

  public function exists()
  {
    return file_exists( $this->getPath() );
  }

  /**
   *
   * @var string
   */
  protected $FilePath;

  /**
   *
   * @var Interfaces\ObjectStorage
   */
  protected $ObjectStorage;

}

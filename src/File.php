<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class File implements \Qck\Interfaces\File, Interfaces\PersistableObject
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
    $this->Changed  = true;
  }

  public function getBasename()
  {
    return pathinfo( $this->FilePath, PATHINFO_BASENAME );
  }

  public function getExtension()
  {
    return pathinfo( $this->FilePath, PATHINFO_EXTENSION );
  }

  public function getFileName()
  {
    return pathinfo( $this->FilePath, PATHINFO_FILENAME );
  }

  public function getPath()
  {
    return $this->FilePath;
  }

  public function isDir()
  {
    return is_dir( $this->FilePath );
  }

  public function readContents()
  {
    $FilePath = $this->FilePath;
    if ( ! file_exists( $FilePath ) || filesize( $FilePath ) == 0 )
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
    file_put_contents( $this->FilePath, $Data, LOCK_EX );
  }

  public function getParentDir()
  {
    return dirname( $this->FilePath );
  }

  static function join( $BasePath, $FileName )
  {
    $Path = $BasePath . DIRECTORY_SEPARATOR . $FileName;

    return strpos( $Path, "\\" ) !== false ? str_replace( "/", "\\", $Path ) : str_replace( "\\", "/", $Path );
  }

  public function getSize()
  {
    return filesize( $this->FilePath );
  }

  public function exists()
  {
    return file_exists( $this->FilePath );
  }

  public function getData()
  {
    return [ "FilePath" => $this->FilePath ];
  }

  public function getId()
  {
    return $this->Id;
  }

  public function hasChanged()
  {
    return $this->Changed;
  }

  public function setData( array $Data )
  {
    $this->FilePath = $Data[ "FilePath" ];
  }

  public function setId( $Id )
  {
    $this->Id = $Id;
  }

  public function setUnchanged()
  {
    $this->Changed = false;
  }

  public function delete()
  {
    if ( $this->exists() )
      unlink( $this->FilePath );
  }

  protected $Id;
  protected $Changed = false;
  protected $FilePath;

}

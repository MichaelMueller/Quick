<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class File implements \Qck\Interfaces\File
{

  function __construct( $DirPath, $FileName )
  {
    $this->DirPath  = $DirPath;
    $this->FileName = $FileName;
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
    return $this->FileName;
  }

  public function getPath()
  {
    return $this->DirPath . "/" . $this->FileName;
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

  protected $DirPath;
  protected $FileName;

}

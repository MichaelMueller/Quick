<?php

namespace Qck;

/**
 * Represents methods for accessing the file system
 *
 * @author muellerm
 */
class FileSystem implements \Qck\Interfaces\FileSystem
{

  function __construct( Interfaces\FileFactory $FileFactory )
  {
    $this->FileFactory = $FileFactory;
  }

  function getFileFactory()
  {
    return $this->FileFactory;
  }

  public function clearFolder( $FilePath )
  {
    $this->deleteInternal( $FilePath, false );
  }

  public function createDir( $FilePath, $DeleteIfExists = false )
  {
    if ( file_exists( $FilePath ) && $DeleteIfExists )
      $this->delete( $FilePath );

    if ( ! file_exists( $FilePath ) )
      mkdir( $FilePath, 0777, true );
  }

  public function createFile( $Name, $Dir = null, $DeleteIfExists = false )
  {
    $Dir      = $Dir ? $Dir : ".";
    $FilePath = $this->join( $Dir, $Name );
    if ( $DeleteIfExists && file_exists( $FilePath ) )
      unlink( $FilePath );
    $this->assureParentDirExists( $FilePath );
    touch( $FilePath );
    return $this->FileFactory->createFileObjectFromPath( $FilePath );
  }

  public function createRandomFile( $NamePrefix = null, $Ext = null, $Dir = null )
  {
    $Dir = $Dir ? $Dir : sys_get_temp_dir();
    $i   = $NamePrefix ? 0 : 1;
    do
    {
      $FilePath = $this->join( $Dir, $NamePrefix . ($i > 0 ? strval( $i ) : "") . ($Ext ? "." . $Ext : "") );
      $i ++;
    }
    while ( file_exists( $FilePath ) );

    $this->assureParentDirExists( $FilePath );
    touch( $FilePath );

    return $this->FileFactory->createFileObjectFromPath( $FilePath );
  }

  public function delete( $FilePath )
  {
    $this->deleteInternal( $FilePath, true );
  }

  public function getFiles( $Dir, $Mode = 0, $Recursive = true, $Extensions = null, $MaxFiles = null )
  {
    $NumFiles = 0;
    return $this->getFilesInternal( $Dir, $Mode, $Recursive, $Extensions, $MaxFiles, $NumFiles );
  }

  public function getFilesInternal( $Dir, $Mode = 0, $Recursive = true, $Extensions = null, $MaxFiles = null, &$NumFiles = 0 )
  {
    if ( ! is_dir( $Dir ) )
      return [];
    $Files  = [];
    $TheDir = $Dir; //realpath($Dir);
    $Handle = opendir( $TheDir );

    while ( false !== ($FileName = readdir( $Handle )) )
    {
      if ( $FileName == "." || $FileName == ".." )
        continue;
      if ( $MaxFiles && $NumFiles >= $MaxFiles )
        break;
      $File = $this->FileFactory->createFileObject( $TheDir, $FileName );
      if ( $File->isDir() )
      {
        if ( $Mode == 0 || $Mode == 2 )
        {
          $Files[] = $File;
          $NumFiles ++;
        }
        if ( $Recursive )
          $Files = array_merge( $Files, $this->getFilesInternal( $File->getPath(), $Mode, $Recursive, $Extensions, $MaxFiles, $NumFiles ) );
      }
      else
      {
        if ( $Mode == 0 || $Mode == 1 )
        {
          if ( is_string( $Extensions ) )
            $Extensions = array ( $Extensions );
          if ( $Extensions == null || (is_array( $Extensions ) && in_array( $File->getExtension(), $Extensions )) )
          {
            $Files[] = $File;
            $NumFiles ++;
          }
        }
      }
    }
    closedir( $Handle );
    return $Files;
  }

  public function getFolderSize( $Dir )
  {
    $size = 0;
    foreach ( glob( rtrim( $Dir, '/' ) . '/*', GLOB_NOSORT ) as $each )
      $size += is_file( $each ) ? filesize( $each ) : $this->getFolderSize( $each );
    return $size;
  }

  public function writeFile( $FilePath, $Data )
  {
    $this->assureParentDirExists( $FilePath );
    file_put_contents( $FilePath, $Data, LOCK_EX );
  }

  protected function deleteInternal( $FilePath, $Delete = true )
  {
    if ( is_dir( $FilePath ) )
    {
      $objects = scandir( $FilePath );
      foreach ( $objects as $object )
      {
        if ( $object != "." && $object != ".." )
        {
          $CurrentFilePath = $this->join( $FilePath, $object );
          $this->delete( $CurrentFilePath, true );
        }
      }
      if ( $Delete )
        rmdir( $FilePath );
    }
    else if ( is_file( $FilePath ) && $Delete )
      unlink( $FilePath );
  }

  protected function assureParentDirExists( $FilePath )
  {
    $dir = dirname( $FilePath );
    if ( ! is_dir( $dir ) )
      $this->createDir( $dir );
  }

  public function move( $path, $newPath )
  {
    $this->assureParentDirExists( $newPath );
    rename( $path, $newPath );

    return true;
  }

  function copy( $path, $newPath )
  {
    $this->assureParentDirExists( $newPath );
    copy( $path, $newPath );

    return true;
  }

  public function writeToFile( $FilePath, $Data )
  {
    $File = $this->FileFactory->createFileObjectFromPath( $FilePath );
    $this->assureParentDirExists( $File->getParentDir() );
    $File->writeContents( $Data );
  }

  public function join( $BasePath, $FileName )
  {
    $Path = $BasePath . DIRECTORY_SEPARATOR . $FileName;

    return strpos( $Path, "\\" ) !== false ? str_replace( "/", "\\", $Path ) : str_replace( "\\", "/", $Path );
  }

  /**
   *
   * @var Interfaces\FileFactory
   */
  protected $FileFactory;

}

<?php

namespace Qck\Ext;

/**
 *
 * @author muellerm
 */
class FileService extends FileInfoService implements \Qck\Interfaces\FileService
{

  public function createDir( $path )
  {
    if ( file_exists( $path ) )
      return false;

    mkdir( $path, 0777, true );
    return true;
  }

  public function createFile( $filePath )
  {
    if ( file_exists( $filePath ) )
      return false;
    $this->assureParentDirExists( $filePath );
    touch( $filePath );
    return true;
  }

  public function writeFile( $filePath, $data )
  {
    $this->assureParentDirExists( $filePath );
    file_put_contents( $filePath, $data, LOCK_EX );
  }

  public function delete( $path )
  {
    $thePath = realpath( $path );
    if ( !file_exists( $thePath ) )
      return false;

    if ( is_dir( $thePath ) )
      $this->rrmdir( $thePath );
    else
      unlink( $thePath );
    return true;
  }

  public function move( $path, $newPath )
  {
    if ( !file_exists( $path ) )
      return false;

    $this->assureParentDirExists( $newPath );
    rename( $path, $newPath );

    return true;
  }

  function copy( $path, $newPath )
  {
    if ( !file_exists( $path ) )
      return false;

    $this->assureParentDirExists( $newPath );
    copy( $path, $newPath );

    return true;
  }

  protected function assureParentDirExists( $path )
  {
    $dir = dirname( $path );
    if ( !is_dir( $dir ) )
      $this->createDir( $dir );
  }

  protected function rrmdir( $dir )
  {
    if ( is_dir( $dir ) )
    {
      $objects = scandir( $dir );
      foreach ( $objects as $object )
      {
        if ( $object != "." && $object != ".." )
        {
          if ( is_dir( $dir . "/" . $object ) )
            $this->rrmdir( $dir . "/" . $object, false );
          else
            unlink( $dir . "/" . $object );
        }
      }

      rmdir( $dir );
    }
  }

  public function clearFolder( $path )
  {
    if ( !is_dir( $path ) )
      return [];
    $files = [];
    $theDir = realpath( $path );
    $handle = opendir( $theDir );

    while ( false !== ($entry = readdir( $handle )) )
    {
      if ( $entry == "." || $entry == ".." )
        continue;

      $filePath = $theDir . DIRECTORY_SEPARATOR . $entry;
      $files[] = $filePath;
    }
    closedir( $handle );
    foreach ( $files as $file )
      $this->delete( $file );
  }
}

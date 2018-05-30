<?php

namespace qck\ext;

/**
 *
 * @author muellerm
 */
class FileInfoService implements interfaces\FileInfoService
{

  public function getFiles( $Dir, $Recursive = false, $OnlyFiles = false )
  {
    if ( !is_dir( $Dir ) )
      return [];
    $files = [];
    $theDir = realpath( $Dir );
    $handle = opendir( $theDir );

    while ( false !== ($entry = readdir( $handle )) )
    {
      if ( $entry == "." || $entry == ".." )
        continue;

      $filePath = $theDir . DIRECTORY_SEPARATOR . $entry;

      if ( $OnlyFiles == false || !is_dir( $filePath ) )
      {
        $files[] = $filePath;
      }

      if ( is_dir( $filePath ) && $Recursive )
      {
        $files = array_merge( $files, $this->getFiles( $filePath, true, $OnlyFiles ) );
      }
    }
    closedir( $handle );
    return $files;
  }

  public function getFolderSize( $dir )
  {

    $size = 0;
    foreach ( glob( rtrim( $dir, '/' ) . '/*', GLOB_NOSORT ) as $each )
    {
      $size += is_file( $each ) ? filesize( $each ) : $this->getFolderSize( $each );
    }
    return $size;
  }

  public function createUniqueFileName( $dir = null, $ext = null, $prefix = null )
  {
    $theDir = $dir ? $dir : sys_get_temp_dir();
    $path = null;
    $i = 0;
    while ( true )
    {
      $path = $theDir . DIRECTORY_SEPARATOR . $prefix;
      if ( is_null( $prefix ) || $i > 0 )
        $path .= uniqid();

      if ( $ext )
        $path .= "." . $ext;
      if ( !file_exists( $path ) )
        break;
      $i++;
    }
    return $path;
  }

  public function getContents( $filePath )
  {
    if ( !file_exists( $filePath ) || filesize( $filePath ) == 0 )
      return null;

    $f = fopen( $filePath, "r" );
    $locked = flock( $f, LOCK_SH );
    $content = fread( $f, filesize( $filePath ) );
    flock( $f, LOCK_UN );
    fclose( $f );
    return $content;
  }
}

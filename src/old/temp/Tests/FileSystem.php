<?php

namespace Qck\Tests;

/**
 * Description of ServiceRepo
 *
 * @author muellerm
 */
class FileSystem implements \Qck\Interfaces\Test
{

  public function getRequiredTests()
  {
    return [];
  }

  function exec( FileSystem $FileSystem, Cleaner $Cleaner )
  {
    $TestFileSystem = new \Qck\FileSystem();
    // Write Functions
    $MyTempDir = sys_get_temp_dir() . "/" . crc32( self::class );
    $Cleaner->addFile( $MyTempDir );
    $TestFileSystem->createDir( $MyTempDir );
    if ( !file_exists( $MyTempDir ) )
      throw new \Exception( sprintf( "Dir %s was not created.", $MyTempDir ) );
  }
}

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

  public function exec( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    /* @var $FileSystem \Qck\Interfaces\FileSystem */
    $FileSystem = $ServiceRepo->get( \Qck\Interfaces\FileSystem::class, \Qck\FileSystem::class );
    /* @var $Cleaner \Qck\Interfaces\Cleaner */
    $Cleaner = $ServiceRepo->get( \Qck\Interfaces\Cleaner::class );

    // Write Functions
    $MyTempDir = sys_get_temp_dir() . "/" . crc32( FileSystemTest::class );
    $Cleaner->addFile( $MyTempDir );
    $FileSystem->createDir( $MyTempDir );
    if ( !file_exists( $MyTempDir ) )
      throw new Exception( sprintf( "Dir %s was not created.", $MyTempDir ) );
  }
}

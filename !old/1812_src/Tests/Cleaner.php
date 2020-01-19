<?php

namespace Qck\Tests;

/**
 * Description of ServiceRepo
 *
 * @author muellerm
 */
class Cleaner implements \Qck\Interfaces\Test
{

  public function getRequiredTests()
  {
    return [];
  }

  function exec( FileSystem $FileSystem, Cleaner $Cleaner )
  {
    $FileSystem = new \Qck\FileSystem();
    // Write Functions
    $File1 = $FileSystem->createRandomFile();
    $File2 = $FileSystem->createRandomFile();
    $File3 = $FileSystem->createRandomFile();
    $File4 = $FileSystem->createRandomFile();

    $TestCleaner = new \Qck\Cleaner();
    $TestCleaner->addFile( $File1 );
    $TestCleaner->addFile( $File2 );
    $TestCleaner->addCallback( function() use($File3, $File4, $FileSystem)
    {
      $FileSystem->delete( $File3 );
      $FileSystem->delete( $File4 );
    } );
    $TestCleaner->tidyUp();
    $TestFailed = is_file( $File1 ) || is_file( $File2 ) || is_file( $File3 ) || is_file( $File4 );
    if ( !$TestFailed )
      throw new Exception( "Deletion did not work" );
  }
}

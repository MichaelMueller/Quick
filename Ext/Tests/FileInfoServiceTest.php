<?php

namespace Qck\Ext\Tests;

/**
 * implementation of a system cmd
 *
 * @author micha
 */
class FileInfoServiceTest extends \Qck\Core\Test
{

  public function getRequiredTests()
  {
    
  }

  public function run( \Qck\Interfaces\AppConfig $Config,
                       &$FilesToBeDeleted = array () )
  {
    $TempDir = $this->getTempFile( true );
    $FilesToBeDeleted[] = $TempDir;
    $FileInfoService = new \Qck\Ext\FileInfoService();
    touch( $TempDir . "/txtfile1.txt" );
    touch( $TempDir . "/txtfile2.txt" );
    touch( $TempDir . "/phpfile1.php" );
    touch( $TempDir . "/phpfile2.php" );
    touch( $TempDir . "/phpfile3.php" );
    $SubDir = $TempDir . "/subdir";
    mkdir( $SubDir );
    touch( $SubDir . "/phpfile4.php" );

    $Files = $FileInfoService->getFiles( $TempDir, true, true );
    $this->assert( count( $Files ) == 6, "FileInfoService::getFiles() seems wrong" );
  }
}

<?php

namespace qck\ext;

/**
 * @author muellerm
 */
class FileTrash implements interfaces\FileTrash
{

  function __construct( interfaces\FileService $FileService )
  {
    $this->FileService = $FileService;
  }

  public function deleteLater( $dirOrFile )
  {
    $this->Files[] = $dirOrFile;
  }

  function run()
  {
    $Files = $this->Files;
    $this->Files = [];
    foreach ( $Files as $file )
    {
      if ( !file_exists( $file ) )
        continue;
      $this->FileService->delete( $file );
    }
  }

  public function __destruct()
  {
    if ( count( $this->Files ) > 0 )
      $this->run();
  }

  protected $Files = [];

  /**
   *
   * @var interfaces\FileService 
   */
  protected $FileService;

}

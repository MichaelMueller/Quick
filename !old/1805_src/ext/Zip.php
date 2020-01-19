<?php

namespace qck\ext;

/**
 * Implementation of ILog
 *
 * @author muellerm
 */
class Zip implements interfaces\Zip
{

  function __construct( interfaces\FileTrash $FileTrash,
                        interfaces\FileService $FileService )
  {
    $this->FileTrash = $FileTrash;
    $this->FileService = $FileService;
  }

  public function extract( $zipFile, $tempDir = null )
  {
    $zip = new \ZipArchive;
    if ( $zip->open( $zipFile ) !== TRUE )
      throw new \Exception( "File " . $zipFile . " is no zip file" ); // ERROR NO ZIP FILE, user cannot change this (because this is done by javascrpt) => exception
    $extractDir = $this->FileService->createUniqueFileName( $tempDir );
    $this->FileService->createDir( $extractDir );
    $this->FileTrash->deleteLater( $extractDir );
    $zip->extractTo( $extractDir );
    $zip->close();
    return $extractDir;
  }

  /**
   *
   * @var interfaces\FileTrash 
   */
  protected $FileTrash;

  /**
   *
   * @var interfaces\FileService 
   */
  protected $FileService;

}

<?php

namespace Qck\Ext;

/**
 * Implementation of ILog
 *
 * @author muellerm
 */
class Zip implements \Qck\Interfaces\Zip
{

  function __construct(
  \Qck\Interfaces\FileService $FileService )
  {
    $this->FileService = $FileService;
  }

  public function extract( $ZipFile, $Dir = null )
  {
    $zip = new \ZipArchive;
    if ( $zip->open( $ZipFile ) !== TRUE )
      throw new \Exception( "File " . $ZipFile . " is no zip file" ); // ERROR NO ZIP FILE, user cannot change this (because this is done by javascrpt) => exception
    $extractDir = $this->FileService->createUniqueFileName( $Dir );
    $this->FileService->createDir( $extractDir );
    $zip->extractTo( $extractDir );
    $zip->close();
    return $extractDir;
  }

  /**
   *
   * @var \Qck\Interfaces\FileService 
   */
  protected $FileService;

}

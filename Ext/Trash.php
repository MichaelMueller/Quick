<?php

namespace Qck\Ext;

/**
 * Session Management for a user
 *
 * @author muellerm
 */
class Trash implements \Qck\Interfaces\Trash
{

  public function addFile( $FilePath )
  {
    $this->Files[] = $FilePath;
  }

  function emptyTrash()
  {
    foreach ( $this->Files as $FilePath )
      $this->deleteFile( $FilePath );
  }

  function deleteFile( $path )
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

  public function __destruct()
  {
    $this->emptyTrash();
  }

  protected $Files = [];

}

<?php

namespace qck\core\abstracts;

/**
 * a DataObject 
 */
abstract class Test implements \qck\core\interfaces\Test
{

  protected function assert( $condition, $messageIfNot = "" )
  {
    if ( !$condition )
      throw new \Exception( "Assertion failed" . ( $messageIfNot ? ": " . $messageIfNot : "" ) );
  }

  protected function warnIf( $condition, $messageIfTrue = "" )
  {
    if ( $condition )
      print ("Warning: " . $messageIfTrue . PHP_EOL );
  }

  protected function getTempDir( $folderName = null, $deleteIfExists = false,
                                 $create = false )
  {
    $TheFolder = $folderName ? $folderName : uniqid();

    $Dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $TheFolder;
    if ( $deleteIfExists && file_exists( $Dir ) )
      $this->rrmdir( $Dir );

    if ( $create )
      mkdir( $Dir );
    return $Dir;
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
}

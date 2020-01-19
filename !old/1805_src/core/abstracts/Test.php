<?php

namespace qck\core\abstracts;

/**
 * a DataObject 
 */
abstract class Test implements \qck\core\interfaces\Test
{

  protected function assertCompareObjects( $Obj1, $Obj2, $strict = false )
  {
    $cond = $strict ? $Obj1 === $Obj2 : $Obj1 == $Obj2;
    $message = "Objects are different: " . print_r( $Obj1, true ) . " vs. " . PHP_EOL . print_r( $Obj2, true );
    $this->assert( $cond, $message );
  }

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

  protected function getTempFile( $dir = false, &$FilesToDelete = null )
  {
    do
    {
      $File = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid();
    }
    while ( file_exists( $File ) );
    $dir ? mkdir( $File, 0777, true ) : touch( $File );
    if ( is_array( $FilesToDelete ) )
      $FilesToDelete[] = $File;
    return $File;
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

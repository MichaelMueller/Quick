<?php

namespace Qck\Core;

/**
 * a DataObject 
 */
abstract class Test implements \Qck\Interfaces\Test
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

  protected function getTempFile( $Folder = false, &$FilesToDelete = [] )
  {
    do
    {
      $File = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid();
    }
    while ( file_exists( $File ) );
    $Folder ? mkdir( $File, 0777, true ) : touch( $File );
    $FilesToDelete[] = $File;
    return $File;
  }

  static function delete( $FileOrFolder )
  {
    if ( is_dir( $FileOrFolder ) )
    {
      $objects = scandir( $FileOrFolder );
      foreach ( $objects as $object )
      {
        if ( $object != "." && $object != ".." )
        {
          if ( is_dir( $FileOrFolder . "/" . $object ) )
            $this->rrmdir( $FileOrFolder . "/" . $object, false );
          else
            unlink( $FileOrFolder . "/" . $object );
        }
      }

      rmdir( $FileOrFolder );
    }
    else if ( is_file( $FileOrFolder ) )
      unlink( $FileOrFolder );
  }
}

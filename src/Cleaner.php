<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Cleaner implements \Qck\Interfaces\Cleaner
{

  function __construct( Interfaces\FileSystem $FileSystem )
  {
    $this->FileSystem = $FileSystem;
  }

  function addFile( $FilePath )
  {
    $this->Jobs[] = $FilePath;
  }

  public function addCallback( callable $Callback )
  {
    $this->Jobs[] = $Callback;
  }

  public function addFunctor( Interfaces\Functor $Functor )
  {
    $this->Jobs[] = $Functor;
  }

  public function tidyUp()
  {
    foreach ( $this->Jobs as $Job )
    {
      if ( is_callable( $Job ) )
        call_user_func( $Job );
      elseif ( $Job instanceof Interfaces\Functor )
        $Job->exec();
      elseif ( file_exists( $Job ) )
        $this->FileSystem->delete( $Job );
    }
  }

  /**
   *
   * @var Interfaces\FileSystem
   */
  protected $FileSystem;

  /**
   *
   * @var array
   */
  protected $Jobs = [];

}

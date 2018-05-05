<?php

namespace qck\node;

/**
 *
 * @author muellerm
 */
class Matcher implements interfaces\Matcher
{

  static function create( callable $Callable )
  {
    return new Matcher( $Callable );
  }

  function __construct( callable $Callable )
  {
    $this->Callable = $Callable;
  }

  public function matches( $value )
  {
    return call_user_func( $this->Callable, $value );
  }

  /**
   *
   * @var callable 
   */
  protected $Callable;

}

<?php

namespace Qck\Html;

/**
 * Description of BootstrapPage
 *
 * @author muellerm
 */
class CallbackTemplate implements \Qck\Interfaces\Template
{

  function __construct( callable $Closure )
  {
    $this->Closure = $Closure;
  }

  public function render()
  {
    return call_user_func( $this->Closure );
  }

  /**
   *
   * @var callable
   */
  private $Closure;

}

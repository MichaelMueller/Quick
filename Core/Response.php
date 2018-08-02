<?php

namespace Qck\Core;

/**
 *
 * @author muellerm
 */
class Response implements \Qck\Interfaces\Response
{

  function __construct( $Output, $ExitCode )
  {
    $this->Output = $Output;
    $this->ExitCode = $ExitCode;
  }

  function send()
  {
    echo $this->Output instanceof \Qck\Interfaces\Template ? $this->Output->render() : $this->Output;
  }

  public function getExitCode()
  {
    return $this->ExitCode;
  }

  /**
   *
   * @var mixed string or Template 
   */
  protected $Output;

  /**
   *
   * @var mixed string or Template 
   */
  protected $ExitCode;

}

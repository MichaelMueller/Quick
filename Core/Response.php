<?php

namespace Qck\Core;

/**
 *
 * @author muellerm
 */
class Response implements \Qck\Interfaces\Response
{

  function __construct( $ExitCode = \Qck\Interfaces\Response::EXIT_CODE_OK,
                        \Qck\Interfaces\Output $Output = null )
  {
    $this->ExitCode = $ExitCode;
    $this->Output = $Output;
  }

  function getExitCode()
  {
    return $this->ExitCode;
  }

  function getOutput()
  {
    return $this->Output;
  }

  /**
   *
   * @var mixed string or Template 
   */
  protected $ExitCode;

  /**
   *
   * @var \Qck\Interfaces\Output
   */
  protected $Output;

}

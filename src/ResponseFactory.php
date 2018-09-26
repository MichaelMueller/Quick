<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class ResponseFactory implements \Qck\Interfaces\ResponseFactory
{

  public function create( Interfaces\Output $Output = null,
                          $ExitCode = Response::EXIT_CODE_OK )
  {
    return new Response( $Output, $ExitCode );
  }
}

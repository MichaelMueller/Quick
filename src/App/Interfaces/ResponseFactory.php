<?php

namespace Qck\App\Interfaces;

/**
 *
 * @author muellerm
 */
interface ResponseFactory
{

  /**
   * @return Response
   */
  function create( Output $Output = null, $ExitCode = Response::EXIT_CODE_OK );
}

<?php

namespace Qck\App\Interfaces;

/**
 *
 * @author muellerm
 */
interface ResponseGuard
{

  /**
   * @return Response
   */
  function getResponse( Output $Output = null, $ExitCode = Response::EXIT_CODE_OK );
}

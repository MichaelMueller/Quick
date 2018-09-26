<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface ResponseFactory extends Service
{

  /**
   * @return Response
   */
  function create( Output $Output = null, $ExitCode = Response::EXIT_CODE_OK );
}

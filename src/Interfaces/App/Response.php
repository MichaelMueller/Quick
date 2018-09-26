<?php

namespace Qck\Interfaces\App;

/**
 *
 * @author muellerm
 */
interface Response
{

  const EXIT_CODE_OK = 200;
  const EXIT_CODE_UNAUTHORIZED = 401;
  const EXIT_CODE_NOT_FOUND = 404;
  const EXIT_CODE_INTERNAL_ERROR = 500;
  const EXIT_CODE_NOT_IMPLEMENTED = 501;

  /**
   * @return Output
   */
  public function getOutput();

  /**
   * @return int
   */
  public function getExitCode();
}

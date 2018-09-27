<?php

namespace Qck\App\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface Controller
{

  /**
   * @return Response
   */
  public function run( \Qck\Interfaces\ServiceRepo $ServiceRepo );
}

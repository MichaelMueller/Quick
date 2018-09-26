<?php

namespace Qck\Interfaces\App;

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

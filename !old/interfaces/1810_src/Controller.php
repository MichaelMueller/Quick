<?php

namespace Qck\Interfaces;

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
  public function handle( \Qck\Request $Request );
}

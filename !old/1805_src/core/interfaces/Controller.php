<?php

namespace qck\core\interfaces;

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
  public function run( \qck\core\interfaces\AppConfig $config );
}

<?php
namespace qck\interfaces;

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
  public function run( \qck\interfaces\AppConfig $config );
}

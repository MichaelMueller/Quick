<?php
namespace qck\interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface PostProcessor
{  
  /**
   * @return bool true if data was processed successfully, false otherwise 
   */
  public function checkRequest( \qck\interfaces\AppConfig $config );
}

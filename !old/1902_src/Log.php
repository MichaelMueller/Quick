<?php

namespace Qck;

/**
 * Description of ServiceRepo
 *
 * @author muellerm
 */
class Log extends \Monolog\Logger implements Interfaces\Log
{

  /**
   * @param string             $name       The logging channel
   * @param HandlerInterface[] $handlers   Optional stack of handlers, the first one in the array is called first, etc.
   * @param callable[]         $processors Optional array of processors
   */
  public function __construct( $name, array $handlers = array (),
                               array $processors = array () )
  {
    parent::__construct( $name, $handlers, $processors );
  }
}

<?php

namespace Qck\Interfaces;

/**
 * Basic interface for a logger
 *
 * @author muellerm
 */
interface Log
{

  /**
   * send a msg to the log. this should be plain message. time or anything will be added by the log
   */
  public function msg( $msg );
}

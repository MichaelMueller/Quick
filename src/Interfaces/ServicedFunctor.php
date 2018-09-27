<?php

namespace Qck\Interfaces;

/**
 * A basic interface for everything that can be echoed
 * @author muellerm
 */
interface ServicedFunctor
{

  /**
   * @return void
   */
  public function exec( ServiceRepo $ServiceRepo );
}

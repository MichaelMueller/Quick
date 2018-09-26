<?php

namespace Qck\Interfaces;

/**
 * A basic interface for everything that can be echoed
 * @author muellerm
 */
interface Template
{
  /**
   * @return string
   */
  public function render();
}

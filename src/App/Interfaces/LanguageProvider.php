<?php

namespace Qck\App\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface LanguageProvider
{

  /**
   * @return string
   */
  public function get();
}

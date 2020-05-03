<?php

namespace Qck\Interfaces;

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
  public function getLanguage();
}

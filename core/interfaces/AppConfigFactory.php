<?php

namespace qck\core\interfaces;

/**
 *
 * @author muellerm
 */
interface AppConfigFactory
{

  /**
   * @return interfaces\AppConfig
   */
  function create();
}

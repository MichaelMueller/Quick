<?php
namespace qck\interfaces;

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

<?php

namespace Qck\Interfaces;

/**
 * A provider of the current host name
 *
 * @author muellerm
 */
interface HostNameProvider
{

  /**
   * @var string
   */
  function getHostName();
}

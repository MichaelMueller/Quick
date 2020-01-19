<?php

namespace Qck\Interfaces;

/**
 * An Config Service Class
 * 
 * @author muellerm
 */
interface HostConfig
{

  /**
   * @return string
   */
  function getProjectDir();

  /**
   * @return string
   */
  function getWorkingDir();

  /**
   * @return string
   */
  function getHostName();
}

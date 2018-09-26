<?php

namespace Qck\Interfaces;

/**
 * An AppConfig Service Class
 * 
 * @author muellerm
 */
interface AppConfig extends Service
{
  /**
   * @return string the name identifying this web app
   */
  function getAppName();

  /**
   * @return string
   */
  function getWorkingDir( $createIfNotExists = true );
  
  /**
   * @return string
   */
  function getHostName();
}

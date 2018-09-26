<?php

namespace Qck\Interfaces\App;

/**
 * An Config Service Class
 * 
 * @author muellerm
 */
interface Config extends Service
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

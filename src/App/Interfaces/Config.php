<?php

namespace Qck\App\Interfaces;

/**
 * An Config Service Class
 * 
 * @author muellerm
 */
interface Config
{

  /**
   * @return string the name identifying this web app
   */
  function getAppName();

  /**
   * @return string
   */
  function getWorkingDir( $createIfNotExists = true );

}

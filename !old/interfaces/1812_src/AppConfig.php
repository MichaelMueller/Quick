<?php

namespace Qck\Interfaces;

/**
 * An Config Service Class
 * 
 * @author muellerm
 */
interface AppConfig
{

  /**
   * @return string the name identifying this web app
   */
  function getAppName();

  /**
   * @return Router
   */
  function getRouter();

  /**
   * @return Mail\AdminMailer
   */
  function getAdminMailer();

  /**
   * @return ErrorController
   */
  function getErrorController();

  /**
   * @return \Qck\Inputs
   */
  function getInputs();

  /**
   * @return bool
   */
  function wasInvokedFromCli();

  /**
   * @return DirectoryConfig
   */
  function showErrors();

  /**
   * @return DirectoryConfig
   */
  function getDirectoryConfig();

  /**
   * 
   * @param \Qck\Interfaces\DirectoryConfig $DirectoryConfig
   */
  function setDirectoryConfig( DirectoryConfig $DirectoryConfig );
}

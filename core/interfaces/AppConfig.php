<?php
namespace qck\core\interfaces;

/**
 * the central class for configuring the application with different service classes
 * 
 * @author muellerm
 */
interface AppConfig extends Linker
{
  /**
   * @return string the name identifying this web app
   */
  function getAppName();
  /**
   * @return ControllerFactory
   */
  function getControllerFactory();
  /**
   * @return string
   */
  function getWorkingDir();
  /**
   * @return string
   */
  function getDataDir($createIfNotExists=true);
  /**
   * @return string
   */
  function getCacheDir($createIfNotExists=true);
  /**
   * @return ErrorController The default error dialog for this application
   */
  function getErrorController();
  /**
   * @return AdminMailer
   */
  function getAdminMailer();
  /**
   * @return string an identifier for the current host. may use gethostname() implementation
   */
  function getHostInfo();
  /**
   * @return array an array of classnames for testcases
   */
  function getTests();
}

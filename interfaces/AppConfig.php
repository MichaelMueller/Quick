<?php
namespace qck\interfaces;

/**
 * the central class for configuring the application with different service classes
 * 
 * @author muellerm
 */
interface AppConfig
{
  /**
   * @return ErrorController The default error dialog for this application
   */
  function getErrorHandler();
  /**
   * @return bool true if exceptions should be mailed or false otherwise
   */
  function sendMailOnException();
  /**
   * @return AdminMailer
   */
  function getAdminMailer();
  /**
   * @return string an identifier for the current host. may use gethostname() implementation
   */
  function getHostInfo();
  /**
   * @return SetupController or null
   */
  function getSetupController();
  /**
   * @return ControllerFactory
   */
  function getControllerFactory();
  /**
   * @return \qck\interfaces\Session
   */
  function getSession();
  /**
   * create link creates a link for this. since e.g. pretty urls are setup inside the webserver and per instance
   * the developer must provide this function for the system to create valid urls
   * 
   * @return string an absolute url for this application conformant to the controller scheme
   */
  function createLink($ControllerClassName, $args=array());
}

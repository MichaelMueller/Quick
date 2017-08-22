<?php
namespace qck\interfaces;

/**
 * the central class for configuring the application with different service classes
 * 
 * @author muellerm
 */
interface AppConfig extends AppBaseConfig
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
}

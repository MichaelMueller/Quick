<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
abstract class DefaultAppConfig implements Interfaces\AppConfig
{

  /**
   * @return Interfaces\Mail\AdminMailer
   */
  function getAdminMailer()
  {
    return null;
  }

  /**
   * @return Interfaces\ErrorController
   */
  function getErrorController()
  {
    return null;
  }

  /**
   * @return Interfaces\DirectoryConfig
   */
  function getDirectoryConfig()
  {
    return $this->DirectoryConfig;
  }

  function setDirectoryConfig(Interfaces\DirectoryConfig $DirectoryConfig)
  {
    $this->DirectoryConfig = $DirectoryConfig;
  }

  function wasInvokedFromCli()
  {
    if (!$this->InvokedFromCli)
      $this->InvokedFromCli = isset($_SERVER['argc']);
    return $this->InvokedFromCli;
  }

  /**
   * @return Interfaces\Mail\AdminMailer
   */
  function getHostName()
  {
    if (!$this->HostName)
      $this->HostName = gethostname();
    return $this->HostName;
  }

  function showErrors()
  {
    return $this->ShowErrors;
  }

  function setShowErrors($ShowErrors)
  {
    $this->ShowErrors = $ShowErrors;
  }

  function setHostName($HostName)
  {
    $this->HostName = $HostName;
  }

  protected $HostName;
  protected $InvokedFromCli;
  protected $ShowErrors = false;

  /**
   *
   * @var Interfaces\DirectoryConfig
   */
  protected $DirectoryConfig;

}

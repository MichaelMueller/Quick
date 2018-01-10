<?php

namespace qck\core\interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface ControllerFactory
{

  /**
   * @return interfaces\Controller or null
   */
  public function getController();
  
  /**
   * @return string the CurrentControllerClassName (no FQCN just the plain class name)
   */
  public function getCurrentControllerClassName();
  
  /**
   * @return string the Get Key used for calling the controller
   */
  public function getQueryKey();
  
  /**
   * @return bool whether the system was invoked via Command Line Interface
   */
  public function usesCli();
  
  /**
   * @return array cmd args if set
   */
  public function getArgv();
}

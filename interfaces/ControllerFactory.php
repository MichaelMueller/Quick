<?php

namespace qck\interfaces;

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
   * @return string the CurrentControllerClassName (no FQDN just the plain class name)
   */
  public function getCurrentControllerClassName();
  
  /**
   * @return string the Get Key used for calling the controller
   */
  public function getQueryKey();
}

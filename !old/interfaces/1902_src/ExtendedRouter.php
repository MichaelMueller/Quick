<?php

namespace Qck\Interfaces;

/**
 *
 * An extension of a standard router capable of handling absolute URLs
 * @author muellerm
 */
interface ExtendedRouter extends Router
{ 

  /**
   * @return string an absolute url
   */
  public function getAbsoluteLink($Route, $args = array());
  
  /**
   * @return Controller or null
   */
  public function getBaseUrl();
  
  /**
   * @return string this will normally default to "index.php"
   */
  public function getFrontControllerScriptName();
}

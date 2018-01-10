<?php

namespace qck\core\interfaces;

/**
 * the central class for configuring the application with different service classes
 * 
 * @author muellerm
 */
interface Linker
{

  /**
   * create link creates a link for this. since e.g. pretty urls are setup inside the webserver and per instance
   * the developer must provide this function for the system to create valid urls
   * 
   * @return string an absolute url for this application conformant to the controller scheme
   */
  function mkLink( $ControllerClassName, $args = array () );

  /**
   * @return string the current controller name
   */
  function getCurrentControllerName();
}

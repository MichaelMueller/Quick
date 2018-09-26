<?php

namespace Qck\Interfaces\App;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface Router
{

  /**
   * @return Controller 
   * @throws \InvalidArgumentException if Controller could not be determined
   */
  public function getController();

  /**
   * @return string the controller namespace
   */
  public function getCurrentControllerFqcn();

  /**
   * 
   * @param string $ControllerFqcn
   * @param array $args
   * @return string A valid Link conformant to this factory
   */
  public function getLink( $ControllerFqcn, $args = array () );
}

<?php

namespace Qck\App\Interfaces;

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
  
  /**
   * Will immediately redirect to another Page
   * @param string $ControllerFqcn
   * @param array $args
   */
  public function redirect( $ControllerFqcn, $args = array () );
}

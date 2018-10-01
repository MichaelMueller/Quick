<?php

namespace Qck\App\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface Router
{

  const DEFAULT_ROUTE = "Start";

  /**
   * 
   * @param string $DefaultRoute the route if no parameter is set
   */
  function setDefaultRoute( $DefaultRoute = self::DEFAULT_ROUTE );

  /**
   * @return string
   */
  public function getCurrentRoute();

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

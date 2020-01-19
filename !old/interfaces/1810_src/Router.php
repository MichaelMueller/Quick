<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface Router
{

  /**
   * @return string
   */
  public function getCurrentRoute();

  /**
   * 
   * @param string $Route
   * @param array $args
   * @return string A valid Link conformant to this factory
   */
  public function getLink( $Route, $args = array () );

  /**
   * Will immediately redirect to another Page
   * @param string $Route
   * @param array $args
   */
  public function redirect( $Route, $args = array () );
}

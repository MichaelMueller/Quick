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
   * the default route if no route is specified by the client
   */
  const DEFAULT_ROUTE = "Start";

  /**
   * @return string
   */
  public function getCurrentRoute();

  /**
   * @param string $Route
   */
  public function setDefaultRoute( $Route );

  /**
   * @return string
   */
  public function getDefaultRoute();

  /**
   * 
   * @param string $Route
   * @return string The Fqcn of the Controller
   */
  public function getFqcn( $Route );

  /**
   * 
   * @param string $Route
   * @param string $ControllerFqcn
   */
  function addRoute( $Route, $ControllerFqcn );

  /**
   * 
   * @param string $Route
   */
  function addProtectedRoute( $Route );

  /**
   * 
   * @param string $Fqcn
   * @return string The route for the fqcn
   */
  public function getRoute( $Fqcn );

  /**
   * Whether this Route is protected from public access
   * @param string $Route
   * @return bool
   */
  public function isProtected( $Route );

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

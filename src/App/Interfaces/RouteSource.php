<?php

namespace Qck\App\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface RouteSource
{

  /**
   * @return string[]
   */
  public function get();

  /**
   * 
   * @param string $Route
   * @return string The Fqcn of the Controller
   */
  public function getFqcn( $Route );

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
}

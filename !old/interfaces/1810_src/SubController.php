<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface SubController extends Controller
{

  /**
   * 
   * @param \Qck\FrontController $FrontController
   */
  public function setFrontController( \Qck\FrontController $FrontController );
}

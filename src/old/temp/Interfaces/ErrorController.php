<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface ErrorController extends Controller
{

  /**
   * 
   * @param int $errorCode
   */
  function setErrorCode( $errorCode );
}

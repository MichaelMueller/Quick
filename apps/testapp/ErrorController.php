<?php

namespace qck\apps\testapp;
use qck\core\Response;

/**
 * Description of ErrorDialog
 *
 * @author micha
 */
class ErrorController implements \qck\interfaces\Controller
{

  public function run( \qck\interfaces\AppConfig $config )
  {
    $errorCode = $this->ErrorCode;
    $error = "Server Error. Code: ".$errorCode;
    
    return new Response($error);
  }

  public function setErrorCode( $errorCode=Response::CODE_SERVER_ERROR )
  {
    $this->ErrorCode = $errorCode;
  }
  
  protected $ErrorCode = null;
}

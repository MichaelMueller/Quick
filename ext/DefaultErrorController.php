<?php

namespace qck\ext;

/**
 * Description of ErrorDialog
 *
 * @author micha
 */
class DefaultErrorController implements \qck\interfaces\ErrorController
{

  public function run( \qck\interfaces\AppConfig $config )
  {
    $errorCode = $this->ErrorCode;
    $error = "Server Error. Code: ".$errorCode;
    
    return new \qck\core\Response( $error, $errorCode );
  }

  public function setErrorCode( $errorCode= \qck\interaces\Response::CODE_SERVER_ERROR )
  {
    $this->ErrorCode = $errorCode;
  }
  
  protected $ErrorCode = null;
}

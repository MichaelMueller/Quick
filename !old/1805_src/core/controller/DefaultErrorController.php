<?php

namespace qck\core\controller;

/**
 * Description of ErrorDialog
 *
 * @author micha
 */
class DefaultErrorController implements \qck\core\interfaces\ErrorController
{

  public function run( \qck\core\interfaces\AppConfig $config )
  {
    $errorCode = $this->ErrorCode;
    $error = "Server Error. Code: " . $errorCode;

    return new \qck\core\Response( $error, $errorCode );
  }

  public function setErrorCode( $errorCode = \qck\core\interfaces\Response::CODE_SERVER_ERROR )
  {
    $this->ErrorCode = $errorCode;
  }

  protected $ErrorCode = null;

}

<?php

namespace qck\apps\helloworld;


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
    $error = "Server Error. Code: " . $errorCode;

    return new \qck\core\Response( $error );
  }

  public function setErrorCode( $errorCode = \qck\interaces\Response::CODE_SERVER_ERROR )
  {
    $this->ErrorCode = $errorCode;
  }

  protected $ErrorCode = null;

}

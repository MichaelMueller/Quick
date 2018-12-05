<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Response implements \Qck\Interfaces\Response
{

  function getExitCode()
  {
    return $this->ExitCode;
  }

  function getOutput()
  {
    return $this->Output;
  }

  public function __construct( \Qck\Interfaces\Output $Output = null,
                               $ExitCode = \Qck\Interfaces\Response::EXIT_CODE_OK )
  {
    $this->ExitCode = $ExitCode;
    $this->Output   = $Output;
  }

  public function send( Interfaces\AppConfig $AppConfig )
  {
    $Output = $this->getOutput();
    if ( $Output !== null )
    {
      if ( $AppConfig->wasInvokedFromCli() == false )
      {
        http_response_code( $this->getExitCode() );
        header( sprintf( "Content-Type: %s; charset=%s", $Output->getContentType(), $Output->getCharset() ) );
        foreach ( $Output->getAdditionalHeaders() as $header )
        {
          header( $header );
        }
      }
      echo $Output->render();
    }
    exit( $this->getExitCode() );
  }

  /**
   *
   * @var mixed string or Template 
   */
  protected $ExitCode;

  /**
   *
   * @var \Qck\Interfaces\Output
   */
  protected $Output;

}

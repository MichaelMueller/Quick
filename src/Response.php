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

  public function __construct( \Qck\Interfaces\Output $Output = null, $ExitCode = \Qck\Interfaces\Response::EXIT_CODE_OK, $HttpResponse = true )
  {
    $this->ExitCode     = $ExitCode;
    $this->Output       = $Output;
    $this->HttpResponse = $HttpResponse;
  }

  public function send()
  {
    $Output = $this->getOutput();
    if ( $Output !== null )
    {
      if ( $this->HttpResponse == true )
      {
        http_response_code( $this->getExitCode() );
        header( sprintf( "Content-Type: %s; charset=%s", $Output->getContentType(), $Output->getCharset() ) );
        foreach ( $Output->getAdditionalHeaders() as $header )
        {
          header( $header );
        }
      }
      echo ($Output instanceof Interfaces\Template) ? $Output->render() : strval( $Output );
    }
    $AppExitCode = 0;
    if ( $this->HttpResponse == false && $this->ExitCode != Response::EXIT_CODE_OK )
      $AppExitCode = $this->ExitCode;
    exit( $AppExitCode );
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

  /**
   *
   * @var \Qck\Interfaces\HttpResponse
   */
  protected $HttpResponse = true;

}

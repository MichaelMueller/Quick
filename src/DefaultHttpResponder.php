<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class DefaultHttpResponder implements \Qck\Interfaces\HttpResponder
{

  public function send( Interfaces\Output $Output, $ExitCode = \Qck\Interfaces\HttpResponder::EXIT_CODE_OK )
  {
    $StringOutput     = $Output->render();
    $ContentType      = $Output->getContentType();
    $Charset          = $Output->getCharset();
    $AdditionalHeader = $Output->getAdditionalHeaders();
    $this->sendInternal( $StringOutput, $ContentType, $Charset, $ExitCode, $AdditionalHeader );
  }

  public function redirect( $Url )
  {
    $Output           = null;
    $ContentType      = null;
    $Charset          = null;
    $ExitCode         = Interfaces\HttpResponder::EXIT_CODE_REDIRECT_FOUND;
    $AdditionalHeader = [ "Location: " . $Url ];
    $this->sendInternal( $Output, $ContentType, $Charset, $ExitCode, $AdditionalHeader );
  }

  protected function sendInternal( $Output, $ContentType, $Charset, $ExitCode, $AdditionalHeader )
  {
    http_response_code( $ExitCode );
    foreach ( $AdditionalHeader as $header )
    {
      header( $header );
    }
    if ( $Output )
    {
      header( sprintf( "Content-Type: %s; charset=%s", $ContentType, $Charset ) );
      echo $Output;
    }

    $AppExitCode = 0;
    if ( $ExitCode != Interfaces\HttpResponder::EXIT_CODE_OK )
      $AppExitCode = $ExitCode;
    exit( $AppExitCode );
  }

}

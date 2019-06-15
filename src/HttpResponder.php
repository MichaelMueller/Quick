<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class HttpResponder implements \Qck\Interfaces\HttpResponder
{

    public function send( Interfaces\Output $Output, $ExitCode = \Qck\Interfaces\HttpResponder::EXIT_CODE_OK )
    {
        $this->sendInternal( $Output->render(), $Output->getContentType(), $Output->getCharset(), $ExitCode, $Output->getAdditionalHeaders() );
    }

    public function redirect( $Url, $ExitCode = HttpResponder::EXIT_CODE_REDIRECT_FOUND )
    {
        $Output = null;
        $ContentType = null;
        $Charset = null;
        $AdditionalHeader = ["Location: " . $Url];
        $this->sendInternal( $Output, $ContentType, $Charset, $ExitCode, $AdditionalHeader );
    }

    protected function sendInternal( $Output, $ContentType, $Charset, $ExitCode, $AdditionalHeader )
    {
        http_response_code( $ExitCode );
        foreach ($AdditionalHeader as $header)
        {
            header( $header );
        }
        if ($Output)
        {
            header( sprintf( "Content-Type: %s; charset=%s", $ContentType, $Charset ) );
            echo $Output;
        }

        $AppExitCode = 0;
        if ($ExitCode != Interfaces\HttpResponder::EXIT_CODE_OK)
            $AppExitCode = $ExitCode;
        exit( $AppExitCode );
    }

}

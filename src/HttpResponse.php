<?php

namespace Qck;

class HttpResponse
{

    const EXIT_CODE_OK                   = 200;
    const EXIT_CODE_BAD_REQUEST          = 400;
    const EXIT_CODE_UNAUTHORIZED         = 401;
    const EXIT_CODE_FORBIDDEN            = 403;
    const EXIT_CODE_NOT_FOUND            = 404;
    const EXIT_CODE_UNPROCESSABLE_ENTITY = 422;
    const EXIT_CODE_INTERNAL_ERROR       = 500;
    const EXIT_CODE_NOT_IMPLEMENTED      = 501;
    const EXIT_CODE_MOVED_PERMANENTLY    = 301;
    const EXIT_CODE_REDIRECT_FOUND       = 302;

    /**
     * 
     * @return \Qck\HttpResponse
     */
    static function new()
    {
        return new HttpResponse();
    }

    public function createContent( $body )
    {
        $this->content = new HttpContent( $this, $body );
        return $this->content;
    }

    public function send( $indent = null)
    {
        $content = $this->content->toString($indent);
        http_response_code( $this->returnCode );
        header( sprintf( "Content-Type: %s; charset=%s", $this->content->contentType(), $this->content->charSet() ) );
        echo $content;
    }

    public function setReturnCode( $returnCode = \Qck\HttpResponse::EXIT_CODE_OK )
    {
        $this->returnCode = $returnCode;
        return $this;
    }

    /**
     *
     * @var HttpContent
     */
    protected $content;

    /**
     *
     * @var string
     */
    protected $returnCode = \Qck\HttpResponse::EXIT_CODE_OK;

}

<?php

namespace Qck;

/**
 * Class representing HttpContent
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class HttpContent implements Snippet
{

    // CONSTANTS
    const CONTENT_TYPE_TEXT_PLAIN               = "text/plain";
    const CONTENT_TYPE_TEXT_HTML                = "text/html";
    const CONTENT_TYPE_TEXT_CSS                 = "text/css";
    const CONTENT_TYPE_TEXT_JAVASCRIPT          = "text/javascript";
    const CONTENT_TYPE_TEXT_CSV                 = "text/csv";
    const CONTENT_TYPE_APPLICATION_JSON         = "application/json";
    const CONTENT_TYPE_APPLICATION_OCTET_STREAM = "application/octet-stream";
    const CHARSET_ISO_8859_1                    = "ISO-8859-1";
    const CHARSET_UTF_8                         = "utf-8";
    const CHARSET_BINARY                        = "binary";

    function __construct( HttpResponse $response, $body = null )
    {
        $this->response = $response;
        $this->body     = $body;
    }

    function setBody( $body ): HttpContent
    {
        $this->body = $body;
        return $this;
    }

    public function response()
    {
        return $this->response;
    }

    public function setCharset( $charSet = \Qck\HttpContent::CHARSET_UTF_8 )
    {
        $this->charSet = $charSet;
        return $this;
    }

    public function setContentType( $contentType = \Qck\HttpContent::CONTENT_TYPE_TEXT_HTML )
    {
        $this->contentType = $contentType;
        return $this;
    }

    function toString( $indent = null, $level=0 )
    {
        return $indent . ($this->body instanceof Snippet ? $this->body->toString() : strval( $this->body ));
    }

    function contentType()
    {
        return $this->contentType;
    }

    function charSet()
    {
        return $this->charSet;
    }

    /**
     *
     * @var HttpResponse
     */
    protected $response;

    /**
     *
     * @var Snippet|string
     */
    protected $body;

    /**
     *
     * @var string
     */
    protected $contentType = \Qck\HttpContent::CONTENT_TYPE_TEXT_HTML;

    /**
     *
     * @var string
     */
    protected $charSet = \Qck\HttpContent::CHARSET_UTF_8;

}

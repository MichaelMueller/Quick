<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Output implements \Qck\Interfaces\Output
{

    function __construct( $Content, $ContentType )
    {
        $this->Content     = $Content;
        $this->ContentType = $ContentType;
    }

    function setCharset( $Charset )
    {
        $this->Charset = $Charset;
    }

    function setAdditionalHeaders( $AdditionalHeaders )
    {
        $this->AdditionalHeaders = $AdditionalHeaders;
    }

    function getAdditionalHeaders()
    {
        return $this->AdditionalHeaders;
    }

    function getCharset()
    {
        return $this->Charset;
    }

    function getContentType()
    {
        return $this->ContentType;
    }

    public function render()
    {
        return is_callable( $this->Content ) ? call_user_func( $this->Content ) : strval( $this->Content );
    }

    protected $Content;
    protected $ContentType;
    protected $Charset           = \Qck\Interfaces\Output::CHARSET_UTF_8;
    protected $AdditionalHeaders = [];

}

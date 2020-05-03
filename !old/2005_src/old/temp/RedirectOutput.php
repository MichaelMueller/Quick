<?php

namespace Qck;

/**
 * Description of ProjectDashboard
 *
 * @author muellerm
 */
class RedirectOutput implements \Qck\Interfaces\Output
{

  function __construct( $RedirectUrl, $ContentType = \Qck\Interfaces\Output::CONTENT_TYPE_APPLICATION_OCTET_STREAM, $Charset = \Qck\Interfaces\Output::CHARSET_UTF_8 )
  {
    $this->RedirectUrl = $RedirectUrl;
    $this->ContentType = $ContentType;
    $this->Charset     = $Charset;
  }

  public function getAdditionalHeaders()
  {
    return [ 'Location: ' . $this->RedirectUrl ];
  }

  public function getCharset()
  {
    return $this->Charset;
  }

  public function getContentType()
  {
    return $this->ContentType;
  }

  public function render()
  {
    return null;
  }

  private $RedirectUrl;
  private $ContentType;
  private $Charset;

}

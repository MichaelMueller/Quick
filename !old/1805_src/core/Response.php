<?php

namespace qck\core;

/**
 *
 * @author muellerm
 */
class Response implements \qck\core\interfaces\Response
{

  /**
   * __construct
   * 
   * @param mixed $Contents
   * @param string $ContentType
   * @param int $ResponseCode
   * @param array $Headers
   */
  function __construct( $Contents = null, $ResponseCode = 200, $Headers = array (),
                        $ContentType = "Content-Type: text/html; charset=utf-8" )
  {
    $this->Contents = $Contents;
    $this->ContentType = $ContentType;
    $this->ResponseCode = $ResponseCode;
    $this->Headers = $Headers;
  }

  function getContentType()
  {
    return $this->ContentType;
  }

  function getHeaders()
  {
    return $this->Headers;
  }

  function getContents()
  {
    return $this->Contents;
  }

  function setContentType( $ContentType )
  {
    $this->ContentType = $ContentType;
  }

  function addHeader( $Header )
  {
    $this->Headers[] = $Header;
  }

  function setHeaders( $Headers )
  {
    $this->Headers = $Headers;
  }

  function setContents( $Contents )
  {
    $this->Contents = $Contents;
  }

  function getResponseCode()
  {
    return $this->ResponseCode;
  }

  function send()
  {
    http_response_code( $this->getResponseCode() );

    foreach ( $this->getHeaders() as $header )
      header( $header );

    $contents = $this->getContents();
    if ( $contents != null )
    {
      header( $this->getContentType() );
      if ( $contents instanceof \qck\core\interfaces\Template )
        echo $contents->render();
      else if ( is_callable( $contents ) )
        echo call_user_func( $contents );
      else
        echo $contents;
    }
    exit( $this->getResponseCode() );
  }

  protected $ResponseCode;
  protected $ContentType;
  protected $Headers;
  protected $Contents;

}

<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Output extends \Qck\Interfaces\Template
{

  // CONSTANTS
  const CONTENT_TYPE_TEXT_PLAIN = "text/plain";
  const CONTENT_TYPE_TEXT_HTML = "text/html";
  const CONTENT_TYPE_TEXT_CSS = "text/css";
  const CONTENT_TYPE_TEXT_JAVASCRIPT = "text/javascript";
  const CONTENT_TYPE_APPLICATION_JSON = "application/json";
  const CONTENT_TYPE_APPLICATION_OCTET_STREAM = "application/octet-stream";
  const CHARSET_ISO_8859_1 = "ISO-8859-1";
  const CHARSET_UTF_8 = "UTF-8";
  const CHARSET_BINARY = "binary";

  /**
   * @return string
   */
  public function getContentType();

  /**
   * @return string
   */
  public function getCharset();

  /**
   * @return array of strings
   */
  public function getAdditionalHeaders();
}

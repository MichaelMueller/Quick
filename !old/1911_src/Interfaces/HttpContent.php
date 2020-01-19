<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface HttpContent
{

  // CONSTANTS
  const CONTENT_TYPE_TEXT_PLAIN = "text/plain";
  const CONTENT_TYPE_TEXT_HTML = "text/html";
  const CONTENT_TYPE_TEXT_CSS = "text/css";
  const CONTENT_TYPE_TEXT_JAVASCRIPT = "text/javascript";
  const CONTENT_TYPE_TEXT_CSV = "text/csv";
  const CONTENT_TYPE_APPLICATION_JSON = "application/json";
  const CONTENT_TYPE_APPLICATION_OCTET_STREAM = "application/octet-stream";
  const CHARSET_ISO_8859_1 = "ISO-8859-1";
  const CHARSET_UTF_8 = "utf-8";
  const CHARSET_BINARY = "binary";
  
  /**
   * @return string The output text string (could be empty or null if its a header only httpresponse)
   */
  public function getContents();
  
  /**
   * @return string
   */
  public function getContentType();

  /**
   * @return string
   */
  public function getCharset();
}

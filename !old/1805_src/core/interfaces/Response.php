<?php

namespace qck\core\interfaces;

/**
 *
 * @author muellerm
 */
interface Response
{

  const CODE_OK = 200;
  const CODE_PAGE_NOT_FOUND = 404;
  const CODE_SESSION_EXPIRED_CODE = 440;
  const CODE_SERVER_ERROR = 500;
  const CODE_UNAUTHORIZED = 401;
  const CT_HTML_UTF8 = "Content-Type: text/html; charset=utf-8";
  const CT_TEXTPLAIN_UTF8 = "Content-Type: text/plain; charset=utf-8";
  const CT_CSS_UTF8 = "Content-Type: text/css; charset=utf-8";
  const CT_JAVASCRIPT_UTF8 = "Content-Type: text/javascript; charset=utf-8";
  const CT_JSON_UTF8 = "Content-Type: application/json; charset=utf-8";

  public function getResponseCode();

  public function getContentType();

  public function getContents();

  public function getHeaders();

  public function send();
}

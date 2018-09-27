<?php

namespace Qck\Mail\Interfaces;

/**
 * Class representing SMTP Parameters
 * @author muellerm
 */
interface Smtp
{

  /**
   * @return string
   */
  function getHost();

  /**
   * @return string
   */
  function getUsername();

  /**
   * @return string
   */
  function getPassword();

  /**
   * @return string "tls", "ssl", null
   */
  function getSmtpSecure();

  /**
   * @return int
   */
  function getPort();

  /**
   * @return int
   */
  function getTimeout();

  /**
   * @return bool
   */
  function getVerfiyCertificates();
}

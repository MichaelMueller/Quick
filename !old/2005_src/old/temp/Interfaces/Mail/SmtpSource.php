<?php

namespace Qck\Interfaces\Mail;

/**
 * Service class for creating Parties
 * @author muellerm
 */
interface SmtpSource
{

  /**
   * 
   * @return Smtp
   */
  function get();
}

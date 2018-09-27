<?php

namespace Qck\Mail\Interfaces;

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

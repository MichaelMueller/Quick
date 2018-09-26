<?php

namespace Qck\Interfaces\Mail;

/**
 * Service class for creating Parties
 * @author muellerm
 */
interface SmtpSource extends \Qck\Interfaces\Service
{

  /**
   * 
   * @return Smtp
   */
  function get();
}

<?php

namespace Qck\Interfaces\Mail;

/**
 * Service class for creating Parties
 * @author muellerm
 */
interface SmtpFactory extends \Qck\Interfaces\Service
{

  /**
   * 
   * @return SmtpFactory
   */
  function create();
}

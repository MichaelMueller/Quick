<?php

namespace Qck\Mail;

/**
 * Description of PhpMailer
 *
 * @author muellerm
 */
class Party implements \Qck\Interfaces\Mail\Party
{

  function __construct( $Name, $MailAddress )
  {
    $this->Name = $Name;
    $this->MailAddress = $MailAddress;
  }

  function getName()
  {
    return $this->Name;
  }

  function getMailAddress()
  {
    return $this->MailAddress;
  }

  /**
   *
   * @var int 
   */
  protected $Name;

  /**
   *
   * @var bool 
   */
  protected $MailAddress;

}

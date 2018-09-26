<?php

namespace Qck\Mail;

/**
 * Description of PhpMailer
 *
 * @author muellerm
 */
class PartyFactory implements \Qck\Interfaces\Mail\PartyFactory
{

  public function create( $Name, $MailAddress )
  {
    return new Party( $Name, $MailAddress );
  }
}

<?php

namespace Qck\Mail;

/**
 * Description of PhpMailer
 *
 * @author muellerm
 */
class PhpMailerMessageFactory implements \Qck\Interfaces\Mail\MessageFactory
{

  function __construct( \Qck\Interfaces\Mail\SmtpSource $SmtpSource )
  {
    $this->SmtpSource = $SmtpSource;
  }

  public function create( array $Recipients, $Text )
  {
    $Message = new PhpMailerMessage( $this->SmtpSource->get(), $Recipients, $Text );
    return $Message;
  }

  /**
   *
   * @var \Qck\Interfaces\Mail\SmtpSource
   */
  protected $SmtpSource;

}

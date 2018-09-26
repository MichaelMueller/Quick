<?php

namespace Qck\Mail;

use Qck\Interfaces\Mail\SmtpFactory;

/**
 * Description of PhpMailer
 *
 * @author muellerm
 */
class PhpMailerMessageFactory implements \Qck\Interfaces\Mail\MessageFactory
{

  function __construct( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $this->ServiceRepo = $ServiceRepo;
  }

  public function create( array $Recipients, $Text )
  {
    /* @var $SmtpFactory SmtpFactory */
    $SmtpFactory = $this->ServiceRepo->get( SmtpFactory::class );
    $Message = new PhpMailerMessage( $SmtpFactory->create(), $Recipients, $Text );

    return $Message;
  }

  /**
   *
   * @var \Qck\Interfaces\ServiceRepo
   */
  protected $ServiceRepo;

}

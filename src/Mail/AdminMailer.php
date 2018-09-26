<?php

namespace Qck\Mail;

use Qck\Interfaces\Mail\MessageFactory;
use Qck\Interfaces\Mail\AdminPartySource;

/**
 * Description of PhpMailer
 *
 * @author muellerm
 */
class AdminMailer implements \Qck\Interfaces\Mail\AdminMailer
{

  function __construct( MessageFactory $MessageFactory, AdminPartySource $AdminPartySource )
  {
    $this->MessageFactory = $MessageFactory;
    $this->AdminPartySource = $AdminPartySource;
  }

  public function sendToAdmin( $Subject, $Text )
  {
    $MessageFactory = $this->MessageFactory;
    $AdminPartySource = $this->AdminPartySource;
    // create the message
    $Message = $MessageFactory->create( [ $AdminPartySource->get() ], $Text );
    $Message->setSubject( $Subject );
    $Message->send();
  }

  /**
   *
   * @var MessageFactory
   */
  protected $MessageFactory;

  /**
   *
   * @var AdminPartySource
   */
  protected $AdminPartySource;

}

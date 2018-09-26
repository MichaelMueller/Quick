<?php

namespace Qck\Mail;

use Qck\Interfaces\Mail\MessageFactory;
use Qck\Interfaces\Mail\PartyFactory;

/**
 * Description of PhpMailer
 *
 * @author muellerm
 */
class AdminMailer implements \Qck\Interfaces\Mail\AdminMailer
{

  function __construct( \Qck\Interfaces\ServiceRepo $ServiceRepo, $AdminName,
                        $AdminAddress )
  {
    $this->ServiceRepo = $ServiceRepo;
    $this->AdminName = $AdminName;
    $this->AdminAddress = $AdminAddress;
  }

  public function sendToAdmin( $Subject, $Text )
  {
    /* @var $MessageFactory MessageFactory */
    $MessageFactory = $this->ServiceRepo->get( MessageFactory::class );
    /* @var $PartyFactory PartyFactory */
    $PartyFactory = $this->ServiceRepo->get( PartyFactory::class );
    $AdminParty = $PartyFactory->create( $this->AdminName, $this->AdminAddress );
    // create the message
    $Message = $MessageFactory->create( [ $AdminParty ], $Text );
    $Message->setSubject( $Subject );
    $Message->send();
  }

  /**
   *
   * @var \Qck\Interfaces\ServiceRepo
   */
  protected $ServiceRepo;

  /**
   *
   * @var string 
   */
  protected $AdminName;

  /**
   *
   * @var string 
   */
  protected $AdminAddress;

}

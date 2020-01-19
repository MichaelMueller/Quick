<?php

namespace Qck\Interfaces\Mail;

/**
 * Service class for creating Messages
 * @author muellerm
 */
interface MessageFactory extends \Qck\Interfaces\Service
{

  /**
   * 
   * @param \Qck\Interfaces\Mail\Party $Sender
   * @param \Qck\Interfaces\Mail\Party[] $Recipients
   * @param string $Text   
   * @return \Qck\Interfaces\Mail\Message
   */
  function create( array $Recipients, $Text );
}

<?php

namespace Qck\Mail\Interfaces;

/**
 * Service class for creating Messages
 * @author muellerm
 */
interface MessageFactory extends \Qck\Interfaces\Service
{

  /**
   * 
   * @param \Qck\Mail\Interfaces\Party $Sender
   * @param \Qck\Mail\Interfaces\Party[] $Recipients
   * @param string $Text   
   * @return \Qck\Mail\Interfaces\Message
   */
  function create( array $Recipients, $Text );
}

<?php

namespace Qck\Mail\Interfaces;

/**
 * Service class for creating Parties
 * @author muellerm
 */
interface PartyFactory extends \Qck\Interfaces\Service
{

  /**
   * 
   * @param string $Name
   * @param string $MailAddress
   * @return Party
   */
  function create( $Name, $MailAddress );
}

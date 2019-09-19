<?php

namespace Qck\Interfaces\Mail;

/**
 * Service class for creating Parties
 * @author muellerm
 */
interface AdminPartySource extends \Qck\Interfaces\Service
{

  /**
   * 
   * @return \Qck\Mail\Party
   */
  function get();
}

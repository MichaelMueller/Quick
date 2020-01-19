<?php

namespace Qck\Interfaces\Mail;

/**
 * Service class for sending messages to an admin
 * @author muellerm
 */
interface AdminMailer extends \Qck\Interfaces\Service
{

  /**
   * send a plain message to the admin of this project
   * @param string $Subject
   * @param string $Text
   */
  public function sendToAdmin( $Subject, $Text );
}

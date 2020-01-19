<?php

namespace Qck\Interfaces;

/**
 * Service class for sending messages to an admin
 * @author muellerm
 */
interface AdminMailer
{

  /**
   * send a plain message to the admin of this project
   * @param string $Subject
   * @param string $Text
   */
  public function sendToAdmin( $Subject, $Text );
}

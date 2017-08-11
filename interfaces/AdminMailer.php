<?php
namespace qck\interfaces;

/**
 *
 * @author muellerm
 */
interface AdminMailer
{
  public function sendToAdmin( $subject, $message);
}

<?php
namespace qck\core\interfaces;

/**
 *
 * @author muellerm
 */
interface AdminMailer
{
  public function sendToAdmin( $subject, $message );
}

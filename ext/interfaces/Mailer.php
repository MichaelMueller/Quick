<?php
namespace qck\ext\interfaces;

/**
 *
 * @author muellerm
 */
interface Mailer
{
  public function send($recepients, $subject, $message, $fromName=null, $isHtml=false, $attachments=array(), $embeddedImages=array());
}

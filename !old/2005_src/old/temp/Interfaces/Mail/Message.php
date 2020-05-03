<?php

namespace Qck\Interfaces\Mail;

/**
 * Class representing an actual mail message
 * @author muellerm
 */
interface Message
{

  /**
   * 
   * @param string $Subject
   */
  function setSubject( $Subject );

  /**
   * 
   * @param string $SenderName
   */
  function setSenderName( $SenderName );

  /**
   * 
   * @param bool $Html
   */
  function setHtml( $Html );

  /**
   * 
   * @param string $FilePath
   */
  function addAttachment( $FilePath );

  /**
   * 
   * @param string $FilePath
   */
  function addEmbeddedImage( $Cid, $FilePath );

  /**
   * send the mail
   */
  function send();
}

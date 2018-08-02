<?php

namespace Qck\Ext;

/**
 * Description of PhpMailer
 *
 * @author muellerm
 */
class PhpMailer implements \Qck\Interfaces\Mailer
{

  function __construct( $host, $username, $password )
  {
    $this->host = $host;
    $this->username = $username;
    $this->password = $password;
  }

  public function send( $recepients, $subject, $message, $fromName = null,
                        $isHtml = false, $attachments = array (),
                        $embeddedImages = array () )
  {
    $mail = new \PHPMailer;
    $mail->isSMTP();
    $mail->Host = $this->host;
    $mail->SMTPAuth = true;
    $mail->Username = $this->username;
    $mail->Password = $this->password;
    $mail->SMTPSecure = $this->smtpSecure;
    $mail->Port = $this->port;
    if ( !$this->VerfiyCertificates )
    {
      $mail->SMTPOptions = array (
        'ssl' => array (
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );
    }
    $fromAddress = $this->fromAddress ? $this->fromAddress : $this->username;
    $fromName2 = $fromName ? $fromName : $this->username;
    $mail->setFrom( $fromAddress, $fromName2 );
    if ( is_string( $recepients ) )
      $recepients = array ( $recepients );
    foreach ( $recepients as $recepient )
    {
      $rAddress = isset( $recepient[ "address" ] ) ? $recepient[ "address" ] : $recepient;
      $rName = isset( $recepient[ "name" ] ) ? $recepient[ "name" ] : null;
      $mail->addAddress( $rAddress, $rName );
    }

    $mail->isHTML( $isHtml );
    $mail->Subject = $subject;
    $mail->Body = $message;

    foreach ( $attachments as $attachment )
      $mail->addAttachment( $attachment );

    foreach ( $embeddedImages as $cid => $embeddedImage )
      $mail->addEmbeddedImage( $embeddedImage, $cid );

    if ( !$mail->send() )
    {
      throw new \Exception( 'Message could not be sent. ' . $mail->ErrorInfo );
    }
  }

  function setHost( $host )
  {
    $this->host = $host;
  }

  function setUsername( $username )
  {
    $this->username = $username;
  }

  function setPassword( $password )
  {
    $this->password = $password;
  }

  function setFromAddress( $fromAddress )
  {
    $this->fromAddress = $fromAddress;
  }

  function setSmtpSecure( $smtpSecure )
  {
    $this->smtpSecure = $smtpSecure;
  }

  function setPort( $port )
  {
    $this->port = $port;
  }

  function setTimeout( $timeout )
  {
    $this->timeout = $timeout;
  }

  function setVerfiyCertificates( $VerfiyCertificates )
  {
    $this->VerfiyCertificates = $VerfiyCertificates;
  }

  // Needed
  protected $host = null;
  protected $username = null;
  protected $password = null;
  //Defaults
  protected $fromAddress = null;
  protected $smtpSecure = "tls";
  protected $port = 587;
  protected $timeout = 5;
  protected $VerfiyCertificates = false;

}

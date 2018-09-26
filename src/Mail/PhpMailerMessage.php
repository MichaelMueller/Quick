<?php

namespace Qck\Mail;

/**
 * Description of PhpMailer
 *
 * @author muellerm
 */
class PhpMailerMessage implements \Qck\Interfaces\Mail\Message
{

  function __construct( \Qck\Interfaces\Mail\Smtp $Smtp, array $Recipients, $Text )
  {
    $this->Smtp = $Smtp;
    $this->Recipients = $Recipients;
    $this->Text = $Text;
  }

  public function send()
  {
    $mail = new \PHPMailer;
    $mail->isSMTP();
    $mail->Host = $this->Smtp->getHost();
    if ( $this->Smtp->getUsername() && $this->Smtp->getPassword() )
    {
      $mail->SMTPAuth = true;
      $mail->Username = $this->Smtp->getUsername();
      $mail->Password = $this->Smtp->getPassword();
    }
    if ( $this->Smtp->getSmtpSecure() )
      $mail->SMTPSecure = $this->Smtp->getSmtpSecure();
    $mail->Port = $this->Smtp->getPort();
    if ( !$this->Smtp->getVerfiyCertificates() )
    {
      $mail->SMTPOptions = array (
        'ssl' => array (
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );
    }
    $SenderAdress = $this->SenderAdress ? $this->SenderAdress : $this->Username;
    $SenderName = $this->SenderName ? $this->SenderName : $this->Username;
    $mail->setFrom( $SenderAdress, $SenderName );
    if ( is_string( $this->Recepients ) )
      $this->Recepients = array ( $this->Recepients );
    foreach ( $this->Recipients as $Recipient )
    {
      $Name = $Recipient->getName();
      $Name = $Name ? $Name : $Recipient->getMailAddress();
      $mail->addAddress( $Recipient->getMailAddress(), $Name );
    }

    $mail->isHTML( $this->Html );
    $mail->Subject = $this->Subject;
    $mail->Body = $this->Text;

    foreach ( $this->Attachments as $FilePath )
      $mail->addAttachment( $FilePath );

    foreach ( $this->EmbeddedImages as $Cid => $FilePath )
      $mail->addEmbeddedImage( $FilePath, $Cid );

    if ( !$mail->send() )
      throw new \Exception( 'Message could not be sent. ' . $mail->ErrorInfo );
  }

  function setSubject( $Subject )
  {
    $this->Subject = $Subject;
  }

  function setHtml( $Html )
  {
    $this->Html = $Html;
  }

  public function addAttachment( $FilePath )
  {
    $this->Attachments[] = $FilePath;
  }

  public function addEmbeddedImage( $Cid, $FilePath )
  {
    $this->EmbeddedImages[ $Cid ] = $FilePath;
  }

  function setSenderName( $SenderName )
  {
    $this->SenderName = $SenderName;
  }

  function setSenderMailAddress( $SenderMailAddress )
  {
    $this->SenderMailAddress = $SenderMailAddress;
  }

  // MANDATORY
  /**
   *
   * @var \Qck\Interfaces\Mail\Smtp 
   */
  protected $Smtp = null;

  /**
   *
   * @var \Qck\Interfaces\Mail\Party[]
   */
  protected $Recipients;

  /**
   *
   * @var string 
   */
  protected $Text;
  //OPTIONAL

  /**
   *
   * @var string
   */
  protected $SenderName;

  /**
   *
   * @var string
   */
  protected $SenderMailAddress;

  /**
   *
   * @var string 
   */
  protected $Subject = "";

  /**
   *
   * @var bool 
   */
  protected $Html = false;

  /**
   *
   * @var string[] 
   */
  protected $Attachments = [];

  /**
   * Map
   * @var string[] 
   */
  protected $EmbeddedImages = [];

}

<?php

/* @var $ServiceRepo Qck\ServiceRepo */
$ServiceRepo = Qck\ServiceRepo::getInstance();

// ADD SERVICES
// add Qck\App
$ServiceRepo->addService( Qck\App::class, function() use($ServiceRepo)
{
  return new Qck\App( $ServiceRepo );
} );

// add Qck\Request
$ServiceRepo->addService( Qck\Request::class, function()
{
  return new Qck\Request();
} );

// add Qck\ResponseFactory
$ServiceRepo->addService( Qck\ResponseFactory::class, function()
{
  return new Qck\ResponseFactory();
} );

// add service
$ServiceRepo->addService( \Qck\Mail\PhpMailerMessageFactory::class, function() use($ServiceRepo)
{
  $SmtpSource = $ServiceRepo->getOptional( Qck\Interfaces\Mail\SmtpSource::class );
  return $SmtpSource ? new \Qck\Mail\PhpMailerMessageFactory( $SmtpSource ) : null;
} );

// add service
$ServiceRepo->addService( \Qck\Mail\PartyFactory::class, function()
{
  return new \Qck\Mail\PartyFactory();
} );

// add service
$ServiceRepo->addService( \Qck\Mail\AdminMailer::class, function() use($ServiceRepo)
{
  $MessageFactory = $ServiceRepo->getOptional( Qck\Interfaces\Mail\MessageFactory::class );
  $AdminPartySource = $ServiceRepo->getOptional( Qck\Interfaces\Mail\AdminPartySource::class );
  return ($MessageFactory && $AdminPartySource) ? new \Qck\Mail\AdminMailer( $MessageFactory, $AdminPartySource ) : null;
} );

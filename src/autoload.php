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
  return new \Qck\Mail\PhpMailerMessageFactory( $ServiceRepo );
} );

// add service
$ServiceRepo->addService( \Qck\Mail\PartyFactory::class, function() use($ServiceRepo)
{
  return new \Qck\Mail\PartyFactory( $ServiceRepo );
} );

// add service
$ServiceRepo->addService( \Qck\Mail\AdminMailer::class, function() use($ServiceRepo)
{
  return new \Qck\Mail\AdminMailer( $ServiceRepo );
} );

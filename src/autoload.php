<?php

/* @var $ServiceRepo Qck\ServiceRepo */
$ServiceRepo = Qck\ServiceRepo::getInstance();

// ADD SERVICES *************
// add Qck\Log
$ServiceRepo->addServiceFactory( Qck\Log::class, function()
{
  $Log = new Qck\Log( Qck\Log::class );
  $Log->pushHandler( new StreamHandler( 'php://stdout', Logger::WARNING ) );
  return $Log;
} );

// add Qck\FileSystem
$ServiceRepo->addServiceFactory( Qck\FileSystem::class, function()
{
  return new \Qck\FileSystem();
} );

// add Qck\Cleaner
$ServiceRepo->addServiceFactory( Qck\Cleaner::class, function() use($ServiceRepo)
{
  return new \Qck\Cleaner( $ServiceRepo->get( \Qck\Interfaces\FileSystem::class ) );
} );

// add Qck\TestDriver
$ServiceRepo->addServiceFactory( Qck\TestDriver::class, function() use($ServiceRepo)
{
  return new \Qck\TestDriver( $ServiceRepo );
} );

// add Qck\TestSuite
$ServiceRepo->addServiceFactory( Qck\TestSuite::class, function()
{
  return new \Qck\TestSuite();
} );


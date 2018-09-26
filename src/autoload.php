<?php

/* @var $ServiceRepo Qck\ServiceRepo */
$ServiceRepo = Qck\ServiceRepo::getInstance();

// ADD SERVICES
// add Qck\Log
$ServiceRepo->addServiceFactory( Qck\Log::class, function()
{
  return new Qck\Log( Qck\Log::class );
} );

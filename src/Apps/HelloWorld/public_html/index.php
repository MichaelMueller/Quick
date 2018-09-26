<?php

// BASIC ERROR REPORTING
ini_set( 'display_errors', 0 );
ini_set( 'log_errors', 1 );
error_reporting( E_ALL );
// this should point to the main PSR-4 Autoloader
require_once(__DIR__ . '/../../../../vendor/autoload.php');

// factories
function createAppConfig()
{
  return new \Qck\AppConfig( "HelloWorldApp" );
}

function createRouter()
{
  $Router = new \Qck\Router( $ServiceRepo );
  $Router->addController( $Router->getDefaultQuery(), \Qck\Apps\HelloWorld\StartController::class );
  return $Router;
}
// add factories
/* @var $ServiceRepo Qck\ServiceRepo */
$ServiceRepo = Qck\ServiceRepo::getInstance();

// add \Qck\AppConfig
$ServiceRepo->addService( \Qck\AppConfig::class, createAppConfig );

// add Router \Qck\Router
$ServiceRepo->addService( \Qck\Router::class, createRouter );

/* @var $App Qck\Interfaces\App */
$App = $ServiceRepo->get( Qck\Interfaces\App::class );

// create & run application
$App->run();

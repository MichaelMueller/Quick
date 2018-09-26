<?php

// BASIC ERROR REPORTING
ini_set( 'display_errors', 1 );
ini_set( 'log_errors', 0 );
error_reporting( E_ALL );
// this should point to the main PSR-4 Autoloader
require_once(__DIR__ . '/../../../../vendor/autoload.php');

// add factories
/* @var $ServiceRepo Qck\ServiceRepo */
$ServiceRepo = Qck\ServiceRepo::getInstance();

// add \Qck\AppConfig
$ServiceRepo->addServiceFactory( \Qck\AppConfig::class, new \Qck\AppConfig( "HelloWorldApp" ) );

// add Router \Qck\Router
$Router = new \Qck\Router( Qck\ServiceRepo::getInstance()->get( Qck\Request::class ) );
$Router->addController( $Router->getDefaultQuery(), \Qck\Apps\HelloWorld\StartController::class );
$ServiceRepo->addServiceFactory( \Qck\Router::class, $Router );

/* @var $App Qck\Interfaces\App */
$App = $ServiceRepo->get( Qck\Interfaces\App::class );

// create & run application
$App->run();

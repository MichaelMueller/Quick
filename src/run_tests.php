<?php

// BASIC ERROR REPORTING
error_reporting( E_ALL );
ini_set( 'log_errors', 0 );
ini_set( 'display_errors', 1 );

// this should point to the main PSR-4 Autoloader
$AutoloadFile1 = __DIR__ . '/../vendor/autoload.php';
$AutoloadFile2 = __DIR__ . '/../../../../vendor/autoload.php';
require_once(file_exists( $AutoloadFile2 ) ? $AutoloadFile2 : $AutoloadFile1);

/* @var $TestDriver Qck\Interfaces\TestDriver */
$TestDriver = \Qck\ServiceRepo::getInstance()->get( Qck\Interfaces\TestDriver::class );

// run test driver
$TestDriver->exec();

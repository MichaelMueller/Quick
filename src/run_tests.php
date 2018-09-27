<?php

// BASIC ERROR REPORTING
error_reporting( E_ALL );
ini_set( 'log_errors', 0 );
ini_set( 'display_errors', 1 );

// this should point to the main PSR-4 Autoloader
require_once(__DIR__ . '/../../vendor/autoload.php');

/* @var $TestDriver Qck\Interfaces\TestDriver */
$TestDriver = \Qck\ServiceRepo::getInstance()->get( Qck\Interfaces\TestDriver::class );

// run test driver
$TestDriver->exec();

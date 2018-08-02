<?php
// BASIC ERROR REPORTING
ini_set( "html_errors", 0 );
error_reporting( E_ALL );

// this should point to the main PSR-4 Autoloader
require_once('../../../vendor/autoload.php');

// create & run application
$app = new \Qck\Core\App ( new \Qck\Apps\TestApp\AppConfig() );
$app->run();

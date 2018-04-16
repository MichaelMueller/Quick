<?php
// BASIC ERROR REPORTING
ini_set( "display_errors", 1 );
ini_set( "log_errors", 0 );
ini_set( "html_errors", 0 );
error_reporting( E_ALL );

// this should point to the main PSR-4 Autoloader
require_once('../../../vendor/autoload.php');

// create & run application
$app = new \qck\core\App(new qck\apps\testapp\AppConfigFactory(".."));
$app->run();
  
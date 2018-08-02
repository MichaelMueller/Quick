<?php

// BASIC ERROR REPORTING
error_reporting( E_ALL );

// this should point to the main PSR-4 Autoloader
require_once('../../../vendor/autoload.php');

// uses
use \Qck\Core\App;
use \Qck\Apps\HelloWorld\AppConfigFactory;

// create & run application
$App = new App( new AppConfigFactory( isset( $argv ) ? $argv : null ) );
$App->run();

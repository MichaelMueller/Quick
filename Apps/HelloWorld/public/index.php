<?php

// BASIC ERROR REPORTING
error_reporting( E_ALL );

// this should point to the main PSR-4 Autoloader
require_once('../../../vendor/autoload.php');

// uses
use \Qck\Core\App;

// create & run application
$App = new App( new \Qck\Apps\HelloWorld\AppConfig() );
$App->run();

<?php

use Qck\Demo\UserDirectoryApp;

require_once "../../../../vendor/autoload.php";
$Args = new \Qck\Arguments();
$App = new UserDirectoryApp\App( new UserDirectoryApp\AppFunctionFactory(), new \Qck\Arguments(), true );
$App->run();

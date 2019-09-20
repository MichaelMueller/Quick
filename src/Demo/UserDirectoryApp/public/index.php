<?php

use Qck\Demo\UserDirectoryApp;

require_once "../../../../vendor/autoload.php";
$Args = new \Qck\Arguments();
$App = new UserDirectoryApp\App( $Args, true );
$App->run();

<?php

use Qck\Demo\HelloWorldApp;

require_once "../../../../vendor/autoload.php";
$Args = new \Qck\Arguments();
$App = new HelloWorldApp\HelloWorldApp( $Args, false );
$App->run();

<?php

require_once "../../../../vendor/autoload.php";

$App = new \Qck\App( new \Qck\Demo\HelloWorldApp\AppFunctionFactory(), new \Qck\Arguments(), false );
$App->run();

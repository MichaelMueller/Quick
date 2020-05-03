<?php

require_once "../../../../vendor/autoload.php";

$App = new \Qck\App( new \Qck\Demo\HelloWorld\AppFunctionFactory(), new \Qck\Arguments(), false );
$App->run();

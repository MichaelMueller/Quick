<?php

use Qck\Demo\AuthenticationDemo;

require_once "../../../../vendor/autoload.php";
$Args = new \Qck\Arguments();
$App = new AuthenticationDemo\App( new AuthenticationDemo\AppFunctionFactory(), new \Qck\Arguments(), true );
$App->run();

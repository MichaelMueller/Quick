<?php

use Qck\Demo\UserManagementApp;

require_once "../../../../vendor/autoload.php";
$Args = new \Qck\Arguments();
$App = new UserManagementApp\UserManagementApp( $Args, false );
$App->run();

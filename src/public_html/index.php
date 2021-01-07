<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Qck\App;

App::createConfig("Demo App", "\\Qck\\DemoAppFunctions")->setShowErrors(true)->setDefaultRoute("HelloWorld")->runApp();


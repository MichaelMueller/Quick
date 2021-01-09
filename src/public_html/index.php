<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Qck\App;

App::createConfig("Demo App", Qck\DemoAppFunctions\HelloWorld::class )->setShowErrors(true)->runApp();


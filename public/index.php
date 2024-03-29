<?php

use NewsApp\Core\App;

ob_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new App();
$app->run();

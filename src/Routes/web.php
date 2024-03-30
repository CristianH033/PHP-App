<?php

use NewsApp\App\Controllers\HomeController;
use NewsApp\App\Controllers\NewsController;
use NewsApp\Core\Request;
use NewsApp\Core\Router;

Router::get('/', [HomeController::class, 'home']);

Router::get('/news', [NewsController::class, 'index']);
Router::get('/news/{id}', [NewsController::class, 'show']);

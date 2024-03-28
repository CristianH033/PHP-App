<?php

use NewsApp\Core\Router;

Router::get('/', function () {
    echo "Hola Mundo";
});

Router::get('path/', function () {
    echo "Path";
});

Router::get('path/other/', function () {
    echo "Other Path";
});

Router::get('/path/{param1}', function ($param1) {
    echo "Param1: {$param1}";
});

Router::get('/path/{param1}/{param2}', function ($param1, $param2) {
    echo "Param1: {$param1} - Param2: {$param2}";
});

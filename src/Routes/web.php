<?php

use NewsApp\Core\Request;
use NewsApp\Core\Router;

Router::get('/', function (Request $request) {
    return view('simple-page', [
        'title' => 'Hello World!',
        'content' => 'Lorem ipsum dolor sit, amet consectetur...'
    ]);
});

Router::get('path/', function (Request $request) {
    return view('simple-page', [
        'title' => 'Path',
        'content' => 'Lorem ipsum dolor sit, amet consectetur...'
    ]);
});

Router::get('path/other/', function (Request $request) {
    return view('simple-page', [
        'title' => 'Other Path',
        'content' => 'Lorem ipsum dolor sit, amet consectetur...'
    ]);
});

Router::get('/path/{param1}', function (Request $request, string $param1) {
    return view('simple-page', [
        'title' => 'Param1: ' . $param1,
        'content' => 'Lorem ipsum dolor sit, amet consectetur...'
    ]);
});

Router::get('/path/{param1}/{param2}', function (Request $request, string $param1, string $param2) {
    return view('simple-page', [
        'title' => 'Param1: ' . $param1 . ' Param2: ' . $param2,
        'content' => 'Lorem ipsum dolor sit, amet consectetur...'
    ]);
});

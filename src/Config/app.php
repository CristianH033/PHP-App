<?php

use NewsApp\Core\Env;

return [
    'name' => Env::get('APP_NAME', 'News App'),
    'env' => Env::get('APP_ENV', 'development'),
    'debug' => Env::get('APP_DEBUG', true),
];

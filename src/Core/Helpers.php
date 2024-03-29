<?php

use NewsApp\Core\Config;
use NewsApp\Core\Env;
use NewsApp\Core\Exceptions\HttpException;
use NewsApp\Core\Request;

function env($key, $default = null): mixed
{
    return Env::get($key, $default);
}

function config($key, $default = null): mixed
{
    return Config::get($key, $default);
}

function request(): Request
{
    return (new Request());
}

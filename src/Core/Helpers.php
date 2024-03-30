<?php

use NewsApp\Core\Config;
use NewsApp\Core\Env;
use NewsApp\Core\Exceptions\HttpException;
use NewsApp\Core\Request;
use NewsApp\Core\View;

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

function abort(int $code, string $message = "Undefined error"): never
{
    throw new HttpException($message, $code);
}

function makeView(string $view, array $data = []): string
{
    return View::make($view, $data);
}

function view(string $view, array $data = []): void
{
    View::render($view, $data);
}

function rootPath(): string
{
    return dirname(__DIR__, 2);
}

function publicPath(): string
{
    return rootPath() . DIRECTORY_SEPARATOR . 'public';
}

function srcPath(): string
{
    return rootPath() . DIRECTORY_SEPARATOR . 'src';
}

function dd(...$args): void
{
    foreach ($args as $arg) {
        var_dump($arg);
    }
}

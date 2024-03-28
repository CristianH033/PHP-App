<?php

namespace NewsApp\Core;

use App\Core\Exceptions\MissingEnvFileException;
use Dotenv\Dotenv;

class Env
{
    public static function load(string $path = null, string $file = null)
    {
        $dotenv = Dotenv::createImmutable($path, $file);
        $dotenv->load();
    }

    public static function get(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }

    public static function getAll()
    {
        return $_ENV;
    }
}

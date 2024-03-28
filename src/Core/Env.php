<?php

namespace NewsApp\Core;

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
        if (isset($_ENV[$key])) {
            return match ($_ENV[$key]) {
                'true' => true,
                'false' => false,
                default => $_ENV[$key],
            };
        }

        return $default;
    }

    public static function set(string $key, $value)
    {
        $_ENV[$key] = $value;
    }

    public static function getAll()
    {
        return $_ENV;
    }
}

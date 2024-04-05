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
            return self::cast($_ENV[$key]);
        }

        return $default;
    }

    private static function cast(string $value): int|float|bool|string|null
    {
        if (is_numeric($value)) {
            if (strpos($value, '.') === false) {
                return (int) $value;
            } else {
                return (float) $value;
            }
        }

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        if (empty($value)) {
            return null;
        }

        return $value;
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

<?php

namespace NewsApp\Core;

class Env
{
    public static function load(string $path = null)
    {
        $path ??= self::getDefaultPath();

        if (!file_exists($path)) {
            return false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = array_map('trim', explode('=', $line, 2));
            $name = self::sanitizeEnvName($name);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
            }
        }

        return true;
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

    protected static function sanitizeEnvName($name)
    {
        return str_replace(['export ', '\'', '"'], '', $name);
    }

    protected static function getDefaultPath()
    {
        $paths = [
            rootPath() . '/.env',
            srcPath() . '/.env',
            publicPath() . '/.env',
            homePath() . '/.env',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return rootPath() . '/.env';
    }
}

<?php

namespace NewsApp\Core;

class Config
{
    protected static $config = [];

    public static function load(string $file)
    {
        $filePath = dirname(__DIR__) . "/Config/{$file}.php";
        $filePath = realpath($filePath);

        if (is_file($filePath)) {
            self::$config = array_merge(self::$config, [$file => require $filePath]);
        } else {
            throw new \Exception("El archivo de configuración {$file}.php no existe.");
        }
    }

    public static function get(string $key, $default = null)
    {
        $array = self::$config;

        if (strstr($key, '.')) {
            $keys = explode('.', $key);
            foreach ($keys as $k) {
                if (array_key_exists($k, $array)) {
                    $array = $array[$k];
                } else {
                    return $default;
                }
            }

            return $array;
        }

        return array_key_exists($key, self::$config) ? self::$config[$key] : $default;
    }
}

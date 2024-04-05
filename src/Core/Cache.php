<?php

namespace NewsApp\Core;

class Cache
{
    private static ?self $instance = null;
    private $enabled;
    private $defaultTtl;

    private function __construct($enabled = true, $defaultTtl = 3600)
    {
        // Constructor privado para evitar instanciación desde fuera
        if (self::$instance) {
            throw new \Exception('La clase Cache ya ha sido instanciada previamente.');
        }

        $this->enabled = $enabled;
        $this->defaultTtl = $defaultTtl;
    }

    public static function getInstance()
    {
        return self::$instance ??= new self(
            config('cache.enabled', true),
            config('cache.default_ttl', 3600)
        );
    }

    public static function get($key)
    {
        if (!self::getInstance()->enabled) {
            return false;
        }

        $value = apcu_fetch($key);
        return $value;
    }

    public static function getAll()
    {
        if (!self::getInstance()->enabled) {
            return false;
        }

        return apcu_cache_info(false);
    }

    public static function function(callable $callback, int|null $ttl = null): mixed
    {
        $ttl ??= self::getInstance()->defaultTtl;

        $trace = debug_backtrace(
            DEBUG_BACKTRACE_PROVIDE_OBJECT,
            2
        );

        if (!self::getInstance()->enabled) {
            return call_user_func($callback, $trace[1]['args']);
        }

        $functionName = $trace[1]['function'];
        $arguments = $trace[1]['args'];
        $normalizedArguments = array_map(fn ($argument) => (is_object($argument) ? spl_object_hash($argument) : $argument), $arguments);
        $objectName = $trace[1]['class'] ?? null;
        $prefix = str_contains(($objectName . $functionName), '{closure}') ? $trace[0]['line'] : ($objectName . $functionName);
        $hash = md5($prefix . serialize($normalizedArguments));

        // $object = (!isset($trace[1]['type'])) ? $trace[0]['file'] : ($trace[1]['type'] === '::' ? $trace[1]['class'] : $trace[1]['object']);
        // if (is_string($object)) {
        //     $object = self::getInstance();
        // }

        if (apcu_exists($hash)) {
            return apcu_fetch($hash);
        }

        $value = call_user_func($callback, $arguments);

        apcu_store($hash, $value, $ttl);

        return $value;
    }

    public static function set($key, $value, $ttl = null)
    {
        $ttl ??= self::getInstance()->defaultTtl;

        if (!self::getInstance()->enabled) {
            return false;
        }

        return apcu_store($key, $value, $ttl);
    }

    public static function delete($key)
    {
        if (!self::getInstance()->enabled) {
            return false;
        }

        return apcu_delete($key);
    }

    public static function clear()
    {
        if (!self::getInstance()->enabled) {
            return false;
        }

        return apcu_clear_cache();
    }

    public static function exists($key)
    {
        if (!self::getInstance()->enabled) {
            return false;
        }

        return apcu_exists($key);
    }

    public static function isEnabled()
    {
        return self::getInstance()->enabled && extension_loaded('apcu') && ini_get('apc.enabled');
    }

    public static function isDisabled()
    {
        return !self::isEnabled();
    }

    public static function disable()
    {
        self::getInstance()->enabled = false;
    }

    public static function enable()
    {
        self::getInstance()->enabled = true;
    }
}

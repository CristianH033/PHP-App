<?php

namespace NewsApp\Core;

use Throwable;

class Log
{
    private static $errorLogFile = 'errors.log';
    private static $systemLogFile = 'system.log';

    public static function logError(Throwable $e): void
    {
        $message = sprintf(
            "[%s] [%s] %s\n%s\n\n",
            date('Y-m-d H:i:s'),
            get_class($e),
            $e->getMessage(),
            $e->getTraceAsString()
        );

        self::writeToFile(self::errorFilePath(), $message);
    }

    public static function logMessage(...$data): void
    {
        $logEntries = array_map(function ($item) {
            return sprintf("[%s] %s", date('Y-m-d H:i:s'), self::formatData($item));
        }, $data);

        $logContent = implode("\n", $logEntries) . "\n";
        self::writeToFile(self::systemFilePath(), $logContent);
    }

    private static function errorFilePath(): string
    {
        return config('log.path', rootPath() . '/logs') . DIRECTORY_SEPARATOR . self::$errorLogFile;
    }

    private static function systemFilePath(): string
    {
        return config('log.path', rootPath() . '/logs') . DIRECTORY_SEPARATOR . self::$systemLogFile;
    }

    private static function formatData($data): string
    {
        if (is_scalar($data)) {
            return (string) $data;
        } elseif (is_array($data) || is_object($data)) {
            return print_r($data, true);
        } else {
            return gettype($data);
        }
    }

    private static function writeToFile($file, $content): void
    {
        $logDir = dirname($file);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
    }
}

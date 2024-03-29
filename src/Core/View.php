<?php

namespace NewsApp\Core;

use League\Plates\Engine;
use RuntimeException;

class View
{
    private static $instance;
    private Engine $engine;

    private function __construct(string $viewsPath)
    {
        self::$instance = $this;
        $this->engine = new Engine($viewsPath);
    }

    public static function getInstance(string $viewsPath = null): self
    {
        if (self::$instance === null) {
            $viewsPath = $viewsPath ?: config('views.views_path');
            new self($viewsPath);
        }

        return self::$instance;
    }

    public static function make(string $view, array $data = []): string
    {
        $instance = self::getInstance();
        return $instance->makeView($view, $data);
    }

    public static function render(string $view, array $data = [])
    {
        $instance = self::getInstance();
        return $instance->renderView($view, $data);
        // echo self::make($view, $data);
    }

    private function makeView(string $view, array $data = []): string
    {
        if (!$this->exists($view)) {
            throw new RuntimeException("La vista '{$view}' no existe.");
        }

        return $this->engine->render($view, $data);
    }

    private function renderView(string $view, array $data = []): void
    {
        if (!$this->exists($view)) {
            throw new RuntimeException("La vista '{$view}' no existe.");
        }

        ob_start();
        echo $this->engine->render($view, $data);
        ob_end_flush();
    }

    private function exists(string $view): bool
    {
        return $this->engine->exists($view);
    }
}

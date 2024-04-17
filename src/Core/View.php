<?php

namespace NewsApp\Core;

use NewsApp\Core\Http\Response;
use NewsApp\Core\Template\Engine;
use RuntimeException;

class View
{
    private static ?View $instance = null;
    private Engine $engine;

    private function __construct(string $viewsPath)
    {
        $this->engine = new Engine($viewsPath);
    }

    public static function getInstance(string $viewsPath = null): self
    {
        if (self::$instance === null) {
            $viewsPath = $viewsPath ?: config('views.views_path');
            self::$instance = new self($viewsPath);
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
    }

    private function makeView(string $view, array $data = []): string
    {
        if (!$this->exists($view)) {
            throw new RuntimeException("La vista '{$view}' no existe.");
        }

        return $this->engine->render($view, $data);
    }

    private function renderView(string $view, array $data = [])
    {
        if (!$this->exists($view)) {
            throw new RuntimeException("La vista '{$view}' no existe.");
        }

        return Response::make()->setBody($this->engine->render($view, $data))->send();
    }

    private function exists(string $view): bool
    {
        return $this->engine->templateExists($view);
    }
}

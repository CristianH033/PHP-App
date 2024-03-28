<?php

namespace NewsApp\Core;

use Exception;

class Router
{
    private static $instance = null;
    private array $routes = [];

    private function __construct()
    {
        // Constructor privado para evitar instanciación desde fuera
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function get(string $uri, callable|array $controllerInfo): void
    {
        self::getInstance()->addRoute('GET', $uri, $controllerInfo);
    }

    public static function post(string $uri, callable|array $controllerInfo): void
    {
        self::getInstance()->addRoute('POST', $uri, $controllerInfo);
    }

    public static function put(string $uri, callable|array $controllerInfo): void
    {
        self::getInstance()->addRoute('PUT', $uri, $controllerInfo);
    }

    public static function delete(string $uri, callable|array $controllerInfo): void
    {
        self::getInstance()->addRoute('DELETE', $uri, $controllerInfo);
    }

    private function addRoute(string $method, string $uri, array | callable $controller): void
    {
        $parsedUri = $this->parseUri($uri);

        $this->routes[$method][$parsedUri['uri']] = [
            'controller' => $controller,
            'parameterNames' => $parsedUri['parameterNames'],
        ];
    }

    private function parseUri(string $uri): array
    {
        $parts = array_filter(explode('/', ltrim($uri, '/')));

        $parsedUri = '';
        $parameterNames = [];

        foreach ($parts as $part) {
            if (preg_match('~^\{([^}]+)\}$~', $part, $matches)) {
                $parameterNames[] = $matches[1];
            }

            $parsedUri .= '/' . $part;
        }

        return ['uri' => $parsedUri, 'parameterNames' => $parameterNames];
    }

    public function dispatch(string $path, string $httpMethod)
    {
        $controllerInfo = $this->getControllerInfo($path, $httpMethod);

        if ($controllerInfo) {
            $controller = $controllerInfo['controller'];
            $parameters = $controllerInfo['parameters'];

            if (is_callable($controller)) {
                return call_user_func_array($controller, array_values($parameters));
            } else if (is_array($controller) && count($controller) == 2) {
                [$controllerClass, $methodName] = $controller;

                $controllerInstance = new $controllerClass();

                return $controllerInstance->$methodName(...array_values($parameters));
            }
        }

        throw new Exception('No se pudo encontrar la ruta', 404);
    }

    private function getControllerInfo($path, $httpMethod): ?array
    {
        $path = rtrim($path, '/');

        foreach ($this->routes[$httpMethod] as $route => $routeInfo) {
            $routeData = self::getInstance()->parseRouteData($route, $path);
            if ($routeData['match']) {
                return ['controller' => $routeInfo['controller'], 'parameters' => $routeData['params']];
            }
        }

        return null;
    }

    private function parseRouteData(string $route, string $path): array
    {
        $route = str_replace('/', '\/', $route);
        preg_match_all('/\{(.*?)\}/', $route, $paramNames);
        $route = preg_replace('/\{.*?\}/', '([^\/]+)', $route);
        if (preg_match("/^$route$/", $path, $matches)) {
            array_shift($matches);
            $params = [];
            foreach ($paramNames[1] as $index => $name) {
                $params[$name] = $matches[$index];
            }
            return ['match' => true, 'params' => $params];
        } else {
            return ['match' => false, 'params' => []];
        }
    }
}

<?php

namespace NewsApp\Core;

use Exception;

class App
{
    private Request $request;
    private Router $router;
    private ExceptionHandler $appExceptionHandler;

    public function __construct()
    {
        $this->appExceptionHandler = new ExceptionHandler();
        $this->request = new Request();
        $this->router = Router::getInstance();

        $this->setApplicationExceptionHandler();
        $this->loadEnvironmentVariables();
        $this->loadRoutes();
    }

    public function run()
    {
        $path = $this->request->getPath();
        $httpMethod = $this->request->getMethod();

        $this->router->dispatch($path, $httpMethod);
    }

    private function loadRoutes()
    {
        $routesFile = __DIR__ . DIRECTORY_SEPARATOR . '../Config/routes.php';
        if (file_exists($routesFile)) {
            require $routesFile;
        } else {
            throw new Exception("Routes file not found " . $routesFile);
        }
    }

    private function setApplicationExceptionHandler()
    {
        set_exception_handler($this->appExceptionHandler);
    }

    private function loadEnvironmentVariables()
    {
        // Cargar variables de entorno desde .env
    }

    private function registerServices()
    {
        // Registrar proveedores de servicios
    }

    private function configureRouting()
    {
        // Configurar el enrutamiento
    }
}

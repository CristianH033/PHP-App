<?php

namespace NewsApp\Core;

use NewsApp\Core\Exceptions\HttpException;
use NewsApp\Core\Exceptions\NotFoundException;
use Throwable;

class ExceptionHandler
{
    public function __invoke(Throwable $exception)
    {
        ob_clean();

        match (true) {
            $exception instanceof NotFoundException => $this->renderErrorPage($exception, 404),
            $exception instanceof HttpException => $this->renderErrorPage($exception, $exception->getCode()),
            default => $this->renderErrorPage($exception),
        };
    }

    private function renderErrorPage(Throwable $exception, int $statusCode = 500)
    {
        http_response_code($statusCode);

        $exceptionName = $exception::class;
        $errorMessage = $exception->getMessage();
        $errorCode = $exception->getCode();
        $trace = $exception->getTraceAsString();

        $errorPage = "
            <!DOCTYPE html>
            <html>
            <head>
                <title>Error</title>
                <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/water.css@2/out/water.css\">
            </head>
            <body>
                <h1>Oops, ha ocurrido un error</h1>
                <h2>{$exceptionName}</h2>
                <p>Código de error: {$errorCode}</p>
                <p>{$errorMessage}</p>
            </body>
            </html>
        ";

        echo $errorPage;
    }
}

<?php

namespace NewsApp\Core;

use Throwable;

class ExceptionHandler
{
    public function __invoke(Throwable $exception)
    {
        $this->renderErrorPage($exception);
    }

    private function renderErrorPage(Throwable $exception, int $statusCode = 500)
    {
        $errorMessage = $exception->getMessage();
        $errorCode = $exception->getCode();
        $trace = $exception->getTraceAsString();

        $errorPage = "
            <!DOCTYPE html>
            <html>
            <head>
                <title>Error</title>
            </head>
            <body>
                <h1>Oops, ha ocurrido un error</h1>
                <p>Código de error: {$errorCode}</p>
                <p>{$errorMessage}</p>
            </body>
            </html>
        ";

        http_response_code($statusCode);

        echo $errorPage;
    }
}

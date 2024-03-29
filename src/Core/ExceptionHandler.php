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

        $data = [
            "exceptionName" => $exception::class,
            "errorMessage" => $exception->getMessage(),
            "errorCode" => $exception->getCode(),
            "trace" => $exception->getTraceAsString(),
        ];

        return view("errors/{$statusCode}", $data);
    }
}

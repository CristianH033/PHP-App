<?php

namespace NewsApp\Core\Exceptions;

class HttpException extends \Exception
{
    public function __construct($message = "Undefined error", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

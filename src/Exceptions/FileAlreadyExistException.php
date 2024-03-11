<?php

namespace Kriss\DataExporter\Exceptions;

use RuntimeException;
use Throwable;

class FileAlreadyExistException extends RuntimeException
{
    public function __construct(public string $filename, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

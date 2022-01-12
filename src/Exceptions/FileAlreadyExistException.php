<?php

namespace Kriss\DataExporter\Exceptions;

use RuntimeException;
use Throwable;

class FileAlreadyExistException extends RuntimeException
{
    public $filename;

    public function __construct(string $filename, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->filename = $filename;

        parent::__construct($message, $code, $previous);
    }
}

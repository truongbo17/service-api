<?php

namespace App\Exceptions;

use Exception;

class InvalidUniqueUserException extends Exception
{
    public function __construct(string $message = 'Invalid unique_user,please add args unique_user.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

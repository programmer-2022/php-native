<?php

require_once __DIR__ . '/ApiException.php';


class ValidationException extends ApiException
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, 400, $previous);
    }
}

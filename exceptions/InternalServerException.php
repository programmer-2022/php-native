<?php

require_once __DIR__ . '/ApiException.php';


class InternalServerException extends ApiException
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, 500, $previous);
    }
}

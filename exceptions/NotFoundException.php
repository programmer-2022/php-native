<?php

require_once __DIR__ . '/ApiException.php';


class NotFoundException extends ApiException
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, 404, $previous);
    }
}
<?php

class ApiException extends Exception
{
    protected $httpStatusCode;

    public function __construct($message, $code = 0, $httpStatusCode = 500, Exception $previous = null)
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }

        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, $code, $previous);
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    public function toJson()
    {
        return json_encode([
            'status' => 'error',
            'message' => $this->getMessage()
        ]);
    }
}

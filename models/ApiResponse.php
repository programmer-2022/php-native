<?php

class ApiResponse
{
    private $data;
    private $errors;
    private $statusCode;

    public function __construct($statusCode = 200, $data = null, $errors = null)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->errors = $errors;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        
        $response = [
            'data' => $this->data,
            'errors' => $this->errors
        ];

        echo json_encode($response);
    }
}

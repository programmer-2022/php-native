<?php

require_once __DIR__ . '/../vendor/autoload.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token {

    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getInstance()->getConnection();
    }

    public static function getToken() {    
        $authorization = apache_request_headers()['Authorization'];
        $authorization = explode(" ", $authorization);
        $token = $authorization[1];
        $decodedToken = JWT::decode($token, new Key('SECRET_KEY', 'HS256'));
        return $decodedToken;
    }

    public function validate() {
        $token = Token::getToken();
        error_log('TOKKENN: ' . $token);
        $sql = "SELECT id FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user;
    }
}



<?php

require_once __DIR__ . '/../vendor/autoload.php'; 

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../database/Connection.php';
require_once __DIR__ . '/../exceptions/InternalServerException.php';

use Firebase\JWT\JWT;


class AuthDAO
{
    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getInstance()->getConnection();
    }

    public function authenticate(string $email, string $password)
    {
        try {
            $sql = "SELECT id, username, email, password_hash FROM users WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $email);

            $stmt->execute();        
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
              
                $now = strtotime('now');
                $key = 'SECRET_KEY';
                $payload = [
                    'exp' => $now + 3600,
                    'data' => $user['id']
                ];
                
                $tokenJWT = JWT::encode($payload, $key, 'HS256');                
                return $tokenJWT;
            }

            return null;
        } catch (PDOException $e) {
            throw new InternalServerException('Error durante la autenticación.', 0, $e);
        }
    }
}

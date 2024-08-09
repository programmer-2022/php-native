<?php

require_once __DIR__ . '/../database/Connection.php';
require_once __DIR__ . '/../dtos/CreateUserDTO.php';

class UsersDAO
{
    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getInstance()->getConnection();
    }

    public function findAll(): array|false
    {
        try {
            $sql = 'SELECT id, username, email FROM users';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;            
        } catch (PDOException $e) {
            error_log('Error al obtener usuarios: ' . $e->getMessage());
            return false;
        }
    }

    public function create(User $user): bool
    {
        try {
            $this->pdo->beginTransaction();

            $sql = 'INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)';
            $stmt = $this->pdo->prepare($sql);
            
            $username = $user->getUsername();
            $email = $user->getEmail();
            $password = $user->getPasswordHash();
    
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
    
            $result = $stmt->execute();

            if ($result) {
                $this->pdo->commit();
                return true;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log('Error al crear el usuario: ' . $e->getMessage());
            return false;
        }
    }
}

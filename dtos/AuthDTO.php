<?php

class AuthDTO
{
    private string $email;
    private string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $this->validateEmail($email);
        $this->password = $this->validatePassword($password);
    }

    private function validateEmail($email)
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Email inválido.';
        }
        return $email;
    }

    private function validatePassword($password)
    {
        if (strlen($password) < 6) {
            return 'La contraseña debe tener al menos 6 caracteres.';
        }
        return $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }
}

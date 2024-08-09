<?php

class UserValidator
{
    public static function validate(CreateUserDTO $userDTO): array
    {
        $errors = [];

        // Validar username
        if (empty($userDTO->getUsername()) || strlen($userDTO->getUsername()) < 3) {
            $errors['username'] = 'El nombre de usuario debe tener al menos 3 caracteres.';
        }

        // Validar email
        if (empty($userDTO->getEmail()) || !filter_var($userDTO->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email no es válido.';
        }

        // Validar password
        if (empty($userDTO->getPassword()) || strlen($userDTO->getPassword()) < 6) {
            $errors['password'] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        return $errors;
    }
}

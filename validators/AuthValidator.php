<?php

class AuthValidator
{
    public static function validate(AuthDTO $authDTO): array
    {
        $errors = [];

        // Validar email
        if (empty($authDTO->getEmail()) || !filter_var($authDTO->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email no es válido.';
        }

        // Validar password
        if (empty($authDTO->getPassword()) || strlen($authDTO->getPassword()) < 6) {
            $errors['password'] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        return $errors;
    }
}

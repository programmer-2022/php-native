<?php

require_once __DIR__ . '/../models/User.php';

class UserMapper
{
    public static function dtoToModel(CreateUserDTO $dto)
    {
        return new User(
            null,
            $dto->getUsername(),
            $dto->getEmail(),
            $dto->getPassword()
        );
    }

    /* public static function modelToDto(User $user)
    {
        return new CreateUserDTO(
            $user->getUsername(),
            $user->getEmail(),
            $user->getPassword()
        );
    } */
}

<?php

require_once __DIR__ . '/../dao/UsersDAO.php';
require_once __DIR__ . '/../dtos/CreateUserDTO.php';
require_once __DIR__ . '/../mappers/UserMapper.php';
require_once __DIR__ . '/../validators/UserValidator.php';
require_once __DIR__ . '/../models/ApiResponse.php';
require_once __DIR__ . '/../exceptions/ApiException.php';
require_once __DIR__ . '/../exceptions/InternalServerException.php';
require_once __DIR__ . '/../exceptions/ValidationException.php';
require_once __DIR__ . '/../exceptions/UnauthorizedException.php';
require_once __DIR__ . '/../utils/GetToken.php';

class UserController
{
    private UsersDAO $usersDAO;

    public function __construct()
    {
        $this->usersDAO = new UsersDAO();
        header('Content-Type: application/json');
    }

    public function findAll()
    {
        try {
            // TODO: Pendiente implementar validacion con JWT
            // $middleware = new Token();
            // if(!$middleware->validate()) {
            //     throw new UnauthorizedException('No estas autorizado');
            // }

            $users = $this->usersDAO->findAll();

            if ($users === false) {
                throw new InternalServerException('Error al obtener los usuarios.');
            }

            $response = new ApiResponse(200, $users);
            $response->send();
        } catch (ApiException $e) {
            $response = new ApiResponse($e->getHttpStatusCode(), null, ['message' => $e->getMessage()]);
            $response->send();
        }
    }
  
    public function create()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $username = $data['username'] ?? null;
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;

            $userDTO = new CreateUserDTO($username, $email, $password);
            $hasError = UserValidator::validate($userDTO);

            if (!empty($hasError)) {
                throw new ValidationException($hasError);
            }

            $hashedPassword = password_hash($userDTO->getPassword(), PASSWORD_BCRYPT);
            $userDTO->setPassword($hashedPassword);
            $user = UserMapper::dtoToModel($userDTO);
            
            error_log('Usuario a crear: ' . print_r($user, true));

            $result = $this->usersDAO->create($user);
            if (!$result) {
                throw new InternalServerException('Error al crear el usuario.');
            }

            $response = new ApiResponse(201, ['message' => 'Usuario creado con éxito']);
            $response->send();
        } catch (ApiException $e) {
            $response = new ApiResponse($e->getHttpStatusCode(), null, ['message' => $e->getMessage()]);
            $response->send();
        }
    }
}

<?php

require_once __DIR__ . '/../dao/AuthDAO.php';
require_once __DIR__ . '/../dtos/AuthDTO.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../validators/AuthValidator.php';
require_once __DIR__ . '/../models/ApiResponse.php';
require_once __DIR__ . '/../exceptions/BadRequestException.php';
require_once __DIR__ . '/../exceptions/UnauthorizedException.php';
require_once __DIR__ . '/../exceptions/InternalServerException.php';

class AuthController
{
    private AuthDAO $authDAO;

    public function __construct()
    {
        $this->authDAO = new AuthDAO();
        header('Content-Type: application/json');
    }

    public function login()
    {
        try {
           
            $data = json_decode(file_get_contents('php://input'), true);
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;

            $authDTO = new AuthDTO($email, $password);
            $hasError = AuthValidator::validate($authDTO);

            if (!empty($hasError)) {
                throw new ValidationException($hasError);
            }

            $token = $this->authDAO->authenticate($email, $password);

            if (!$token) {
                throw new UnauthorizedException('Credenciales inválidas');
            }

            $response = new ApiResponse(
                200, 
                ['token' => $token],
                null
            );

            $response->send();
        } catch (BadRequestException | UnauthorizedException $e) {
            http_response_code($e->getHttpStatusCode());
            $response = new ApiResponse($e->getHttpStatusCode(), $e->getMessage());
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            $response = new ApiResponse(500, 'Error interno del servidor');
            echo json_encode($response);
        }
    }

    public function logout()
    {
        try {
            session_start();
            session_unset();
            session_destroy();

            $response = new ApiResponse(200, 'Cierre de sesión exitoso');
            http_response_code(200);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            $response = new ApiResponse(500, 'Error interno del servidor');
            echo json_encode($response);
        }
    }
}

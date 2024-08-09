<?php

require_once __DIR__ . '/../controllers/AuthController.php';

$authController = new AuthController();

return [
    '/auth/login' => ['controller' => $authController, 'method' => 'login'],
    'auth/logout' => ['controller' => $authController, 'method' => 'logout']
];


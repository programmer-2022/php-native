<?php

require_once __DIR__ . '/../controllers/UserController.php';

$userController = new UserController();

return [
    '/users' => ['controller' => $userController, 'method' => 'findAll'],
    '/users/create' => ['controller' => $userController, 'method' => 'create'],
    '/users/update/(\d+)' => ['controller' => $userController, 'method' => 'update'],
    '/users/delete/(\d+)' => ['controller' => $userController, 'method' => 'delete'],
    '/users/(\d+)' => ['controller' => $userController, 'method' => 'show'],
];

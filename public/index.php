<?php

require_once __DIR__ . '/../utils/response.php';
require_once __DIR__ . '/../exceptions/ApiException.php';

$request = $_SERVER['REQUEST_URI'];
$request = strtok($request, '?');

$routes = [];

$userRoutes = include __DIR__ . '/../routes/userRoutes.php';
if (is_array($userRoutes)) {
    $routes = array_merge($routes, $userRoutes);
}

$authRoutes = include __DIR__ . '/../routes/authRoutes.php';
if (is_array($authRoutes)) {
    $routes = array_merge($routes, $authRoutes);
}

$found = false;
foreach ($routes as $pattern => $route) {
    if (preg_match("#^$pattern$#", $request, $matches)) {
        array_shift($matches);
        call_user_func_array([$route['controller'], $route['method']], $matches);
        $found = true;
        break;
    }
}

if (!$found) {
    http_response_code(404);
    jsonResponse(['status' => 'error', 'message' => '404 Not Found']);
}

# Configuracion de Excepciones Globales
set_exception_handler( function (Throwable $exception) {
    http_response_code($exception instanceof ApiException ? $exception->getHttpStatusCode() : 500);

    echo $exception instanceof ApiException ? $exception->toJson() : json_encode([
        'status' => 'error',
        'message' => 'Un error inesperado ocurrió.'
    ]);
    exit();
}
);
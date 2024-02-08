<?php

use Blog\Defox\Blog\Exceptions\AppException;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Http\Actions\Users\CreateUser;
use Blog\Defox\Http\Actions\Users\FindByUsername;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\SuccessfulResponse;

require_once __DIR__ . '/vendor/autoload.php';

$connect = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'),);

$routes = [
    'GET' => [
        '/users/show' => new FindByUsername(
            new SqliteUsersRepository($connect)
        )
    ],
    'POST' => [
        '/users/create' => new CreateUser(
            new SqliteUsersRepository($connect)
        )
    ]
];

try {
    // Пытаемся получить путь из запроса
    $path = $request->path();
} catch (HttpException) {
    // Отправляем неудачный ответ,
    // если по какой-то причине не можем получить путь
    (new ErrorResponse)->send();
    return;
}

try {
    // Пытаемся получить HTTP-метод запроса
    $method = $request->method();
} catch (HttpException) {
    // Возвращаем неудачный ответ,
    // если по какой-то причине
    // не можем получить метод
    (new ErrorResponse)->send();
    return;
}

// Если у нас нет маршрутов для метода запроса -
// возвращаем неуспешный ответ
if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}
// Ищем маршрут среди маршрутов для этого метода
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}
// Выбираем действие по методу и пути
$action = $routes[$method][$path];
try {
    $response = $action->handle($request);
} catch (AppException $e) {
    (new ErrorResponse($e->getMessage()))->send();
}
$response->send();

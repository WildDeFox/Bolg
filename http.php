<?php

use Blog\Defox\Blog\Exceptions\AppException;
use Blog\Defox\Blog\Repositories\CommentRepository\CommentRepository;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Http\Actions\Comments\CreateComments;
use Blog\Defox\Http\Actions\Posts\CreatePosts;
use Blog\Defox\Http\Actions\Posts\DeletePosts;
use Blog\Defox\Http\Actions\Posts\FindByUuid;
use Blog\Defox\Http\Actions\Comments\FindCommentByUuid;
use Blog\Defox\Http\Actions\Users\CreateUser;
use Blog\Defox\Http\Actions\Users\FindByUsername;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

// Получаем путь
try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

// Получаем метод
try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/posts/show' => FindByUuid::class,
        'comments/show' => FindCommentByUuid::class,
    ],
    'POST' => [
        '/users/create' => CreateUser::class,
        '/posts/create' => CreatePosts::class,
        '/comments/create' => CreateComments::class,
    ],
    'DELETE' => [
        '/posts/delete' => DeletePosts::class,
    ]
];



// Если у нас нет маршрутов для метода запроса -
// возвращаем неуспешный ответ
if (!array_key_exists($method, $routes)) {
    (new ErrorResponse("Routes not found: $method $path"))->send();
    return;
}
// Ищем маршрут среди маршрутов для этого метода
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("Route not found: $method, $path"))->send();
    return;
}

// Получаем имя класса действия для маршрута
$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    (new ErrorResponse($e->getMessage()))->send();
}
$response->send();
<?php

use Blog\Defox\Blog\Exceptions\AppException;
use Blog\Defox\Blog\Repositories\CommentRepository\CommentRepository;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Http\Actions\Auth\Login;
use Blog\Defox\Http\Actions\Comments\CreateComments;
use Blog\Defox\Http\Actions\CommentsLike\CreateCommentLike;
use Blog\Defox\Http\Actions\Posts\CreatePosts;
use Blog\Defox\Http\Actions\Posts\DeletePosts;
use Blog\Defox\Http\Actions\Posts\FindByUuid;
use Blog\Defox\Http\Actions\Comments\FindCommentByUuid;
use Blog\Defox\Http\Actions\PostsLike\CreatePostLike;
use Blog\Defox\Http\Actions\Users\CreateUser;
use Blog\Defox\Http\Actions\Users\FindByUsername;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';

$logger = $container->get(LoggerInterface::class);

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

// Получаем путь
try {
    $path = $request->path();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

// Получаем метод
try {
    $method = $request->method();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/posts/show' => FindByUuid::class,
        '/comments/show' => FindCommentByUuid::class,
    ],
    'POST' => [
        '/login' => Login::class,
        '/users/create' => CreateUser::class,
        '/posts/create' => CreatePosts::class,
        '/comments/create' => CreateComments::class,
        '/posts/like/create' => CreatePostLike::class,
        '/comment/like/create' => CreateCommentLike::class
    ],
    'DELETE' => [
        '/posts/delete' => DeletePosts::class,
    ]
];



// Если у нас нет маршрутов для метода запроса -
// возвращаем неуспешный ответ.
// Ищем маршрут среди маршрутов для этого метода
if (!array_key_exists($method, $routes) || !array_key_exists($path, $routes[$method])) {
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}

// Получаем имя класса действия для маршрута
$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    (new ErrorResponse($e->getMessage()))->send();
}
$response->send();
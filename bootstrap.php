<?php

use Blog\Defox\Blog\Container\DIContainer;
use Blog\Defox\Blog\Repositories\AuthTokensRepository\AuthTokensRepository;
use Blog\Defox\Blog\Repositories\AuthTokensRepository\AuthTokensRepositoryInterface;
use Blog\Defox\Blog\Repositories\CommentRepository\CommentRepository;
use Blog\Defox\Blog\Repositories\CommentRepository\CommentRepositoryInterface;
use Blog\Defox\Blog\Repositories\LikeCommentRepository\LikeCommentRepository;
use Blog\Defox\Blog\Repositories\LikeCommentRepository\LikeCommentRepositoryInterface;
use Blog\Defox\Blog\Repositories\LikePostRepository\LikePostRepository;
use Blog\Defox\Blog\Repositories\LikePostRepository\LikePostRepositoryInterface;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepositoryInterface;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Http\Auth\AuthenticationInterface;
use Blog\Defox\Http\Auth\BearerTokenAuthentication;
use Blog\Defox\Http\Auth\IdentificationInterface;
use Blog\Defox\Http\Auth\JsonBodyUsernameIdentification;
use Blog\Defox\Http\Auth\JsonBodyUuidIdentification;
use Blog\Defox\Http\Auth\PasswordAuthentication;
use Blog\Defox\Http\Auth\PasswordAuthenticationInterface;
use Blog\Defox\Http\Auth\TokenAuthenticationInterface;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/vendor/autoload.php';

// Загружаем переменные окружения из файла .env
Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/' . $_ENV['SQLITE_DB_PATH'])
);

$container->bind(
    LoggerInterface::class,
    (new Logger('blog'))
    ->pushHandler(new StreamHandler(
        __DIR__ . '/logs/blog.log'
    ))
    ->pushHandler(new StreamHandler(
        __DIR__ . '/logs/blog.error.log',
        level: Logger::ERROR,
        bubble: false,
    ))
);

$container->bind(
    TokenAuthenticationInterface::class,
    BearerTokenAuthentication::class
);

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);
$container->bind(
    AuthTokensRepositoryInterface::class,
    AuthTokensRepository::class
);

$container->bind(
    AuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    IdentificationInterface::class,
    JsonBodyUsernameIdentification::class
);

$container->bind(
    PostRepositoryInterface::class,
    PostRepository::class
);

$container->bind(
    UserRepositoryInterface::class,
    SqliteUsersRepository::class
);

$container->bind(
    CommentRepositoryInterface::class,
    CommentRepository::class
);

$container->bind(
    LikePostRepositoryInterface::class,
    LikePostRepository::class
);

$container->bind(
    LikeCommentRepositoryInterface::class,
    LikeCommentRepository::class
);

return $container;
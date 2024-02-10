<?php

use Blog\Defox\Blog\Container\DIContainer;
use Blog\Defox\Blog\Repositories\LikePostRepository\LikePostRepository;
use Blog\Defox\Blog\Repositories\LikePostRepository\LikePostRepositoryInterface;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepositoryInterface;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
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
    LikePostRepositoryInterface::class,
    LikePostRepository::class
);

return $container;
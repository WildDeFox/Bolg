<?php

use Blog\Defox\Blog\Comment;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\Repositories\CommentRepository\CommentRepository;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Blog\User;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Person\Name;

include __DIR__ . "/vendor/autoload.php";

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');
$userRepository = new SqliteUsersRepository($connection);
$postRepository = new PostRepository($connection);
$commentRepository = new CommentRepository($connection);
$faker = Faker\Factory::create('ru_RU');


$post = $postRepository->get(new UUID('177358a0-8452-439c-851b-66b3f94c990f'));
$comment = $commentRepository->get(new UUID('59d0212f-bd4a-430d-943d-59b829068b32'));
echo $comment;
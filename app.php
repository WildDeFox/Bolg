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
//$userRepository = new SqliteUsersRepository($connection);
//$postRepository = new PostRepository($connection);
//$commentRepository = new CommentRepository($connection);
//$faker = Faker\Factory::create('ru_RU');
//    $faker->realText();
//
//$post = $postRepository->get(new UUID('177358a0-8452-439c-851b-66b3f94c990f'));
//$comment = $commentRepository->get(new UUID('59d0212f-bd4a-430d-943d-59b829068b32'));
//echo $comment;
$userRepository = new SqliteUsersRepository($connection);
$user = $userRepository->getByUsername('defox');

$postRepository = new PostRepository($connection);
$post = $postRepository->get(new UUID('0340fff7-cb33-4e9f-9e68-20a879f9257d'));

$commentRepository = new CommentRepository($connection);
$comment = $commentRepository->get(new UUID('2af634b4-ca27-4fe7-9e01-7aa9d9691dac'));
echo $comment;
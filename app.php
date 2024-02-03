<?php

use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Blog\UUID;

include __DIR__ . "/vendor/autoload.php";

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');
$faker = Faker\Factory::create('ru_RU');

$userRepository = new SqliteUsersRepository($connection);
//$userRepository->save(new User(
//    UUID::random(),
//    new Name(
//        $faker->firstName('female'),
//        $faker->lastName()),
//    'user'
//));
$user = $userRepository->getByUsername('user');

$postRepository = new PostRepository($connection);
$postRepository->save(new Post(
    UUID::random(),
    $user->uuid(),
    'Тест',
    'Привет Мир'
));
//echo $postRepository->get(new UUID('30be9d39-c2f6-499f-b7d8-f4045d2ba243'));

//try {
//    echo $userRepository->get(new UUID('c6d5544e-6c37-4323-9191-87f789927063'));
//    echo $userRepository->getByUsername('admin');
//} catch (Exception $e) {
//    echo $e;
//}

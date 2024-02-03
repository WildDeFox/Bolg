<?php

use Blog\Defox\Blog\Repositories\SqliteUsersRepository;
use Blog\Defox\Blog\User;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Person\Name;

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
try {
    echo $userRepository->get(new UUID('c6d5544e-6c37-4323-9191-87f789927063'));
    echo $userRepository->getByUsername('admin');
} catch (Exception $e) {
    echo $e;
}

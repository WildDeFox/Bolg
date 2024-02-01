<?php

use Blog\Defox\Blog\Comment;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\User;
use Blog\Defox\Person\Name;

include __DIR__ . "/vendor/autoload.php";

$faker = Faker\Factory::create('ru_RU');
[$firstName, $lastName, $part] = explode(" ",  $faker->name());

$name = new Name($firstName, $lastName, $part);

$user = new User(1, $name);
$post = new Post(1, $user, 'Первый пост', 'Привет Мир!');
$comment = new Comment(1, $user, $post, $faker->text(20));

switch ($argv[1])
{
    case "user":
        echo "Пользователь: " . $user->getName();
        break;
    case "post":
        echo "Пользователь: " . $user->getName() . ' опубликовал пост: ' . $post->getText();
        break;
    case "commentaries":
        echo "Пользователь: " . $user->getName() . ' оставил комментарий к посту - ' . $post->getTitle() . ' : ' . $comment->getText();
        break;
}
<?php

namespace Blog\Defox\Blog\Repositories\PostRepository;

use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\PostNotFoundException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Blog\UUID;
use PDO;
use PDOStatement;

readonly class PostRepository implements PostRepositoryInterface
{
    public function __construct(
        private PDO $connect
    )
    {
    }

    public function save(Post $post): void
    {
        $statement = $this->connect->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text) VALUES
            (:uuid, :user_uuid, :title, :text)'
        );
        $statement->execute([
            ':uuid' => $post->uuid(),
            ':user_uuid' => $post->getUser()->uuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }

    /**
     * @throws PostNotFoundException|InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Post
    {
        $statement = $this->connect->prepare(
            'SELECT * FROM posts WHERE uuid = ?'
        );
        $statement->execute([$uuid]);

        return $this->getPost($statement, $uuid);
    }

    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException|UserNotFoundException
     */
    private function getPost(PDOStatement $statement, $errorMessage): Post
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new PostNotFoundException(
                "Пост: $errorMessage не найден"
            );
        }

        $userRepositories = new SqliteUsersRepository($this->connect);
        $user = $userRepositories->get(new UUID($result['author_uuid']));

        return new Post(
            new UUID($result['uuid']),
            $user,
            $result['title'],
            $result['text']
        );
    }
}
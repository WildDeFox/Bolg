<?php

namespace Blog\Defox\Blog\Repositories\PostRepository;

use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\PostNotFoundException;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\UUID;
use PDO;

class PostRepository implements PostRepositoryInterface
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
            ':user_uuid' => $post->getUserId(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }

    /**
     * @throws PostNotFoundException|InvalidArgumentException
     */
    public function get(UUID $uuid): Post
    {
        $statement = $this->connect->prepare(
            'SELECT * FROM posts WHERE uuid = ?'
        );
        $statement->execute([$uuid]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new PostNotFoundException(
                "Пост: $uuid не найден"
            );
        }
        return new Post(
            new UUID($result['uuid']),
            new UUID($result['author_uuid']),
            $result['title'],
            $result['text']
        );
    }
}
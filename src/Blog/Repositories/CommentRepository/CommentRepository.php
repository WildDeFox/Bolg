<?php

namespace Blog\Defox\Blog\Repositories\CommentRepository;

use Blog\Defox\Blog\Comment;
use Blog\Defox\Blog\Exceptions\CommentNotFoundException;
use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\UUID;
use PDO;

readonly class CommentRepository
{
    public function __construct(
        private PDO $connect
    )
    {
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connect->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES 
                    (:uuid, :post_uuid, :author_uuid, :text)'
        );
        $statement->execute([
            ':uuid' => $comment->uuid(),
            ':post_uuid' => $comment->getPostUuid(),
            ':author_uuid' => $comment->getUserUuid(),
            ':text' => $comment->getText()
        ]);
    }

    /**
     * @throws CommentNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): Comment
    {
        $statement = $this->connect->prepare(
            'SELECT * FROM comments WHERE uuid = ?'
        );
        $statement->execute([$uuid]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new CommentNotFoundException(
                "Комментарий: $uuid не найден"
            );
        }
        return new Comment(
            new UUID($result['uuid']),
            new UUID($result['post_uuid']),
            new UUID($result['author_uuid']),
            $result['text']
        );
    }
}
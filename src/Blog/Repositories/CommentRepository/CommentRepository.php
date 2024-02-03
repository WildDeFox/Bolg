<?php

namespace Blog\Defox\Blog\Repositories\CommentRepository;

use Blog\Defox\Blog\Comment;
use Blog\Defox\Blog\Exceptions\CommentNotFoundException;
use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\PostNotFoundException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Blog\UUID;
use PDO;
use PDOStatement;

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
            ':post_uuid' => $comment->getPost()->uuid(),
            ':author_uuid' => $comment->getUser()->uuid(),
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

        return $this->getComment($statement, $uuid);
    }

    /**
     * @throws CommentNotFoundException
     * @throws InvalidArgumentException|UserNotFoundException|PostNotFoundException
     */
    private function getComment(PDOStatement $statement, $errorString): Comment
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new CommentNotFoundException(
                "Комментарий: $errorString не найден"
            );
        }

        $userRepository = new SqliteUsersRepository($this->connect);
        $user = $userRepository->get(new UUID($result['author_uuid']));

        $postRepository = new PostRepository($this->connect);
        $post = $postRepository->get(new UUID($result['post_uuid']));

        return new Comment(
            UUID::random(),
            $post,
            $user,
            $result['text']
        );
    }
}
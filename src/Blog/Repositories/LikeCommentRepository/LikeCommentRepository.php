<?php

namespace Blog\Defox\Blog\Repositories\LikeCommentRepository;

use Blog\Defox\Blog\Exceptions\CommentNotFoundException;
use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\LikeCommentAlreadyExistsException;
use Blog\Defox\Blog\Exceptions\LikesNotFoundException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\LikeComment;
use Blog\Defox\Blog\Repositories\CommentRepository\CommentRepository;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Blog\UUID;
use PDO;

readonly class LikeCommentRepository implements LikeCommentRepositoryInterface
{
    public function __construct(
        private PDO $connection,
    )
    {
    }

    public function save(LikeComment $likeComment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments_like (uuid, comment_uuid, author_uuid) VALUES 
                                                                (:uuid, :comment_uuid, :author_uuid)'
        );

        $statement->execute([
            ':uuid' => $likeComment->uuid(),
            ':comment_uuid' => $likeComment->getComment()->uuid(),
            ':author_uuid' => $likeComment->getUser()->uuid(),
        ]);
    }

    /**
     * @throws LikesNotFoundException
     * @throws InvalidArgumentException
     * @throws CommentNotFoundException
     * @throws UserNotFoundException
     */
    public function getByCommentsUuid(UUID $uuid): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments_like WHERE comment_uuid = :uuid'
        );

        $statement->execute([':uuid' => $uuid]);

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new LikesNotFoundException(
                'No likes to comment with uuid = : ' . $uuid
            );
        }
        $likes = [];
        foreach ($result as $like) {
            $commentRepository = new CommentRepository($this->connection);
            $comment = $commentRepository->get(new UUID($like['comment_uuid']));
            $userRepository = new SqliteUsersRepository($this->connection);
            $user = $userRepository->get(new UUID($like['author_uuid']));
            $likes[] = new LikeComment(
                uuid: new UUID($like['uuid']),
                user: $user,
                comment: $comment,
            );
        }
        return $likes;
    }

    /**
     * @throws LikeCommentAlreadyExistsException
     */
    public function checkLikeForCommentExist($commentUuid, $userUuid): void
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments_like WHERE comment_uuid = :comment_uuid AND author_uuid = :author_uuid'
        );

        $statement->execute([
            ':comment_uuid' => $commentUuid,
            ':author_uuid' => $userUuid,
        ]);

        $isExisted = $statement->fetch();

        if ($isExisted) {
            throw new LikeCommentAlreadyExistsException(
                'The users like for this comment already exists'
            );
        }
    }
}
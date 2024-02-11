<?php

namespace Blog\Defox\Blog\Repositories\LikePostRepository;

use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\LikePostAlreadyExistsExceptions;
use Blog\Defox\Blog\Exceptions\LikesNotFoundException;
use Blog\Defox\Blog\Exceptions\PostNotFoundException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\LikePost;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepositoryInterface;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Blog\UUID;
use PDO;

readonly class LikePostRepository implements LikePostRepositoryInterface
{
    public function __construct(
        private PDO $connection,
    )
    {
    }

    public function save(LikePost $likePost): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts_like (uuid, post_uuid, author_uuid) VALUES 
                                                          (:uuid, :post_uuid, :author_uuid)'
        );

        $statement->execute([
            ':uuid' => $likePost->uuid(),
            ':post_uuid' => $likePost->getPost()->uuid(),
            ':author_uuid' => $likePost->getUser()->uuid(),
        ]);
    }

    /**
     * @throws UserNotFoundException
     * @throws LikesNotFoundException
     * @throws InvalidArgumentException
     * @throws PostNotFoundException
     */
    public function getByPostUuid(UUID $postUuid): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts_like WHERE post_uuid = :uuid'
        );

        $statement->execute([
            ':uuid' => $postUuid
        ]);

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new LikesNotFoundException(
                'No likes to post with uuid = : ' .$postUuid
            );
        }
        $likes = [];
        foreach ($result as $like) {
            $postRepository = new PostRepository($this->connection);
            $post = $postRepository->get(new UUID($like['post_uuid']));
            $userRepository = new SqliteUsersRepository($this->connection);
            $user = $userRepository->get(new UUID($like['author_uuid']));
            $likes[] = new LikePost(
                uuid: new UUID($like['uuid']),
                post: $post,
                user: $user,
            );
        }
        return $likes;
    }

    /**
     * @throws LikePostAlreadyExistsExceptions
     */
    public function checkUserLikeForPostExist($postUuid, $userUuid): void
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts_like WHERE post_uuid = :post_uuid AND author_uuid = :author_uuid'
        );

        $statement->execute([
            ':post_uuid' => $postUuid,
            'author_uuid' => $userUuid
        ]);

        $isExisted = $statement->fetch();

        if ($isExisted) {
            throw new LikePostAlreadyExistsExceptions(
                'The users like for this post already exists'
            );
        }
    }
}
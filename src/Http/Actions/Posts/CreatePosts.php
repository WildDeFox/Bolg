<?php

namespace Blog\Defox\Http\Actions\Posts;

use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepositoryInterface;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Http\Actions\ActionInterface;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\Response;
use Blog\Defox\Http\SuccessfulResponse;
use PDO;
use Psr\Log\LoggerInterface;

class CreatePosts implements ActionInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private PDO $connection,
        private LoggerInterface $logger,
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $newPostUuid = UUID::random();
            $userRepository = new SqliteUsersRepository($this->connection);
            $user = $userRepository->get(new UUID($request->jsonBodyField('author_uuid')));

            $post = new Post(
                $newPostUuid,
                $user,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->postRepository->save($post);
        $this->logger->info("Post created: $newPostUuid");
        return new SuccessfulResponse([
            'uuid' => $newPostUuid,
        ]);
    }
}
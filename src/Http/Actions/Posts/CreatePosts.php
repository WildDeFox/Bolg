<?php

namespace Blog\Defox\Http\Actions\Posts;

use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepositoryInterface;
use Blog\Defox\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Http\Actions\ActionInterface;
use Blog\Defox\Http\Auth\AuthenticationInterface;
use Blog\Defox\Http\Auth\IdentificationInterface;
use Blog\Defox\Http\Auth\TokenAuthenticationInterface;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\Response;
use Blog\Defox\Http\SuccessfulResponse;
use PDO;
use Psr\Log\LoggerInterface;

readonly class CreatePosts implements ActionInterface
{
    public function __construct(
        private TokenAuthenticationInterface $authentication,
        private PostRepositoryInterface $postRepository,
        private LoggerInterface         $logger,
    )
    {
    }

    public function handle(Request $request): Response
    {
        $author = $this->authentication->user($request);

        try {
            $newPostUuid = UUID::random();

            $post = new Post(
                $newPostUuid,
                $author,
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
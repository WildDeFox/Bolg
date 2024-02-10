<?php

namespace Blog\Defox\Http\Actions\PostsLike;

use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\LikePostAlreadyExistsExceptions;
use Blog\Defox\Blog\LikePost;
use Blog\Defox\Blog\Repositories\LikePostRepository\LikePostRepository;
use Blog\Defox\Blog\Repositories\LikePostRepository\LikePostRepositoryInterface;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepositoryInterface;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Http\Actions\ActionInterface;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\Response;
use Blog\Defox\Http\SuccessfulResponse;

readonly class CreatePostLike implements ActionInterface
{
    public function __construct(
        private LikePostRepositoryInterface $likePostRepository,
        private PostRepositoryInterface     $postRepository,
        private UserRepositoryInterface     $userRepository,
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     * @throws HttpException
     */
    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->jsonBodyField('post_uuid');
            $userUuid = $request->jsonBodyField('author_uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->likePostRepository->checkUserLikeForPostExist($postUuid, $userUuid);
        } catch (LikePostAlreadyExistsExceptions $e) {
                return new ErrorResponse($e->getMessage());
        }

        $newLikeUuid = UUID::random();

        $like = new LikePost(
            $newLikeUuid,
            $this->postRepository->get(new UUID($request->jsonBodyField('post_uuid'))),
            $this->userRepository->get(new UUID($request->jsonBodyField('author_uuid')))
        );

        $this->likePostRepository->save($like);

        return new SuccessfulResponse([
            'uuid' => $newLikeUuid,
            'Статус' => 'Лайк успешно поставлен)'
        ]);
    }
}
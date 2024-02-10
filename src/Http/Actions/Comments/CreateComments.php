<?php

namespace Blog\Defox\Http\Actions\Comments;

use Blog\Defox\Blog\Comment;
use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Repositories\CommentRepository\CommentRepositoryInterface;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepositoryInterface;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Http\Actions\ActionInterface;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\Response;
use Blog\Defox\Http\SuccessfulResponse;

class CreateComments implements ActionInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PostRepositoryInterface $postRepository,
        private CommentRepositoryInterface $commentRepository,
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        try {
            $commentUuid = UUID::random();
            $user = $this->userRepository->get(new UUID($request->jsonBodyField('author_uuid')));
            $post = $this->postRepository->get(new UUID($request->jsonBodyField('post_uuid')));

            $comment = new Comment(
                $commentUuid,
                $post,
                $user,
                $request->jsonBodyField('text')
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->commentRepository->save($comment);
        return new SuccessfulResponse([
            'uuid' => $commentUuid,
            'Статус' => 'Новый комментарий успешно создан'
        ]);
    }
}
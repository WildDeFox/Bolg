<?php

namespace Blog\Defox\Http\Actions\CommentsLike;

use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\LikeCommentAlreadyExistsException;
use Blog\Defox\Blog\LikeComment;
use Blog\Defox\Blog\Repositories\CommentRepository\CommentRepositoryInterface;
use Blog\Defox\Blog\Repositories\LikeCommentRepository\LikeCommentRepository;
use Blog\Defox\Blog\Repositories\LikeCommentRepository\LikeCommentRepositoryInterface;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Http\Actions\ActionInterface;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\Response;
use Blog\Defox\Http\SuccessfulResponse;

class CreateCommentLike implements ActionInterface
{
    public function __construct(
        private LikeCommentRepositoryInterface $likeCommentRepository,
        private CommentRepositoryInterface     $commentRepository,
        private UserRepositoryInterface        $userRepository,
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
            $commentUuid = $request->jsonBodyField('comment_uuid');
            $userUuid = $request->jsonBodyField('author_uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->likeCommentRepository->checkLikeForCommentExist($commentUuid, $userUuid);
        } catch (LikeCommentAlreadyExistsException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newLikeUuid = UUID::random();

        $like = new LikeComment(
            $newLikeUuid,
            $this->userRepository->get(new UUID($request->jsonBodyField('author_uuid'))),
            $this->commentRepository->get(new UUID($request->jsonBodyField('comment_uuid'))),
        );

        $this->likeCommentRepository->save($like);

        return new SuccessfulResponse([
            'Статус' => 'Лайк успешно создан))'
        ]);
    }
}